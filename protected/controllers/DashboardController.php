<?php

class DashboardController extends Controller
{
	public function actionIndex()
	{
		error_reporting(E_ALL); ini_set('display_errors', 1);
		if ( ! empty($_POST))
		{
			if (in_array($_POST['game_type'], array(1,2,3)))
			{
				$whitePlayer = new \Libs\Player("white");
				$whitePlayer->setType(\Libs\Player::HUMAN);
				$whitePlayer->setId(Yii::app()->user->id);

				if ($_POST['game_type'] == 1) // Player vs Player
				{	
					$blackPlayer = new \Libs\Player("black");
					$blackPlayer->setType(\Libs\Player::HUMAN);
					$blackPlayer->setId(Yii::app()->user->id);
				}
				elseif ($_POST['game_type'] == 2) // Player vs CPU
				{
					$blackPlayer = new \Libs\Player("black");
					$blackPlayer->setType(\Libs\Player::AI);
				}
				elseif ($_POST['game_type'] == 3) // CPU vs CPU
				{
					$blackPlayer = new \Libs\Player("black");
					$blackPlayer->setType(\Libs\Player::AI);
					
					$whitePlayer->setType(\Libs\Player::AI);
				}
			}
			
			$chessBoard = new \Libs\ChessBoard;
			$chessBoard->settleUpPiecesForNewGame();
			
			$game = new \Libs\ChessGame($whitePlayer, $blackPlayer, $chessBoard);
			
			
			$gameModel = new Game();
			
			$gameModel->Data = $game;
			$gameModel->is_finished = 0;
			$gameModel->game_hash = $gameModel->generateHash();
		
			
			if ( ! $gameModel->insert())
			{
				Yii::app()->user->setFlash('error', "Internal error occurred (22)");
			}
			else
			{
				$this->redirect(array('dashboard/game', 'id' => $gameModel->id));
			}
		}
		
		$this->render('index');
	}

	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'accessControl',
		);
	}
	
	public function accessRules()
    {
        return array(
            array('deny',
                'users'=>array('?'),
            ),
            array('allow',
                'actions'=>array('*'),
                'roles'=>array('@'),
            ),
        );
    }
	
	
	public function actionGame($id)
	{
		$user_id = Yii::app()->user->id;
		
		$game = Game::model()->findByPk($id);
		
		if ( ! $game)
		{
			throw new CHttpException(404);
		}
		
		/* @var $game Game */
		if ($game->Data->getWhitePlayer()->getId() != $user_id
				&& $game->Data->getBlackPlayer()->getId() != $user_id)
		{
			throw new CHttpException(404, "You're not part of this game");
		}
		
		$engine = new \Libs\GameEngine($game->Data);
		$response_array = array();
		
		if ( ! empty($_POST))
		{
			$moveForm = new MoveForm;
			$moveForm->attributes = $_POST;
			
			if ( ! $moveForm->validate())
			{
				$response_array['status'] = 'error';
				$response_array['message'] = Yii::t('Invalid movement requested');
			}
			else
			{
				$processor = new MoveProcessor;
				
				$result = $processor->process($moveForm, $game);
				
				if ($result == MoveProcessor::NO_ERROR)
				{
					$game->save();
					
					// Check if we just made check-mate ? 
					$opponentsKing	= $game->Data->getChessBoard()->findChessPiece(new \Libs\ChessPiece("king", $engine->getPlayerWhoseTurnIsNow()->getColor()));
					
					if ($engine->isSquareUnderAttack($opponentsKing) && $engine->isKingUnderCheckMate($opponentsKing))
					{
						Yii::app()->user->setFlash('notice', Yii::t('success', "Congratulations ! You have sucessfully check-mated your opponent !"));
					}
				}
				else
				{
					switch ($result)
					{
						case MoveProcessor::ERROR_INVALID_MOVEMENT:
							Yii::app()->user->setFlash('error', Yii::t('error', "Invalid movement requested !"));
							break;
						
						case MoveProcessor::ERROR_NOT_YOUR_TURN:
							Yii::app()->user->setFlash('error', Yii::t('error', "It is not your turn !"));
							break;
						
						case MoveProcessor::ERROR_CHECKMATE:
							Yii::app()->user->setFlash('error', Yii::t('error', "Check-mate ! Game over!"));
							break;
						
						case MoveProcessor::ERROR_GAME_FINISHED:
							Yii::app()->user->setFlash('error', Yii::t('error', "This game is finished !"));
							break;
						
						default:
							Yii::app()->user->setFlash('error', Yii::t('error', "Unknown error occured (43) !"));
							Yii::log("Unrecognized errur code received from MoveProcessor: $result", CLogger::LEVEL_ERROR);
							break;
					}
				}
			}
		}
		else
		{
			if ( ! $game->is_finished && $engine->getPlayerWhoseTurnIsNow()->getType() == \Libs\Player::AI)
			{
				$uci = new \Libs\UCI();
				
				$move_array = array();

				// TOTO: Set AI's SKILL LEVEL !!!
				
				foreach ($game->Data->getAllMovements() AS $movement)
				{
					/* @var $movement Movement */
					if ($movement->isSpecialMove())
					{
						if ($movement->getSpecialMove() == 'castle-kingSide')
						{
							if ($movement->getFrom()->getLocation()->getRow() == 1)
							{
								$move_array[] = "e1g1";
							}
							else
							{
								$move_array[]	= "e8g8";
							}
						}
						elseif($movement->getSpecialMove() == 'castle-queenSide')
						{
							if ($movement->getFrom()->getLocation()->getRow() == 1)
							{
								$move_array[] = "e1c1";
							}
							else
							{
								$move_array[]	= "e8c8";
							}
						}
					}
					else
					{
						/* @var $movement \Libs\Movement */
						$move_array[]	= $movement->getFrom()->getLocation()->getColumn() . $movement->getFrom()->getLocation()->getRow()
							 . $movement->getTo()->getLocation()->getColumn() . $movement->getTo()->getLocation()->getRow()
						;
					}

				}
				
				$ai_result	= $uci->getBestMove($move_array);
				
				if ( ! isset($ai_result['bestmove']))
				{
					Yii::log("[Game ID: {$game->id}] AI didn't respond. Moves array: " . print_r($move_array, true), CLogger::LEVEL_ERROR);
					
					throw new CHttpException(500, Yii::t('error', "AI didn't respond in a timely manner!"));
				}
				
				$aiMove = new MoveForm;
				
				$aiMove->from = $ai_result['bestmove'][0] . $ai_result['bestmove'][1];
				$aiMove->to = $ai_result['bestmove'][2] . $ai_result['bestmove'][3];
				
				$processor = new MoveProcessor;
				
				$result = $processor->process($aiMove, $game, true);
				
				if ($result == MoveProcessor::NO_ERROR)
				{
					$game->save();
				}
			}
			
			if ( ! $game->is_finished)
			{
				// Check for check-mate
				$ourKing	= $game->Data->getChessBoard()->findChessPiece(new \Libs\ChessPiece("king", $engine->getPlayerWhoseTurnIsNow()->getColor()));

				if ($engine->isKingUnderCheckMate($ourKing))
				{
					$game->is_finished = true;

					$game->save();

					Yii::app()->user->setFlash('error', Yii::t('error', "Your king is under check mate ! Game Finished"));

					$response_array['is_check_mate']	= true;
				}
			}
			else
			{
				Yii::app()->user->setFlash('notice', Yii::t('info', "This game is finished"));
			}
		}
		
		
		
		
		
		
		
		foreach ($game->Data->getChessBoard()->getAllChessPieces() AS $square)
		{
			/* @var $square \Libs\ChessBoardSquare */
			$response_array['pieces'][]	= array(
				'location' => "{$square->getLocation()->getColumn()},{$square->getLocation()->getRow()}",
				'piece'	=> "{$square->getChessPiece()->getType()},{$square->getChessPiece()->getColor()}",
			);
		}
		
//		var_dump($game->Data->getAllMovements());
//		
//		die();
		foreach ($game->Data->getAllMovements() AS $move)
		{
			
			
			/* @var $move \Libs\Movement */
			$response_array['pieces'][]	= array(
				'from' => "{$move->getFrom()->getLocation()->getColumn()},{$move->getFrom()->getLocation()->getRow()}",
				'to' => "{$move->getTo()->getLocation()->getColumn()},{$move->getTo()->getLocation()->getRow()}",
				'specialMove' => $move->getSpecialMove(),
				'piece'	=> ( ! $move->isSpecialMove() ? "{$move->getChessPiece()->getType()},{$move->getChessPiece()->getColor()}" : ""),
			);
		}
		
		
		
		if ($engine->getPlayerWhoseTurnIsNow()->getId() == $user_id && $engine->getPlayerWhoseTurnIsNow()->getType() == \Libs\Player::HUMAN)
		{
			$response_array['is_your_turn'] = true;
		}
		else
		{
			$response_array['is_your_turn'] = false;
		}
		
		$this->layout = false;
		
		$chessGame = $game->Data;
	
		if (Yii::app()->request->isAjaxRequest)
		{
			
		}
		else
		{
			$this->render("game", array('game' => $chessGame, 'drawHelper' => new \Libs\SimpleDrawHelper(), 'gameId' => $game->id, 'ajaxResponse' => $response_array));
		}
		
		
		
	}
	
}