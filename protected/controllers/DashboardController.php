<?php

class DashboardController extends Controller
{
	public function actionIndex()
	{
		error_reporting(E_ALL); ini_set('display_errors', 1);
		if ( ! empty($_POST))
		{
			if (in_array($_POST['game_type'], array(1,2)))
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
				Yii::app()->user->setFlash('error', "Interlan error occurred (22)");
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
		
		foreach ($game->Data->getChessBoard()->getAllChessPieces() AS $square)
		{
			/* @var $square \Libs\ChessBoardSquare */
			$response_array['pieces'][]	= array(
				'location' => "{$square->getLocation()->getColumn()},{$square->getLocation()->getRow()}",
				'piece'	=> "{$square->getChessPiece()->getType()},{$square->getChessPiece()->getColor()}",
			);
		}
		
		foreach ($game->Data->getAllMovements() AS $move)
		{
			/* @var $move \Libs\Movement */
			$response_array['pieces'][]	= array(
				'from' => "{$move->getFrom()->getLocation()->getColumn()},{$move->getFrom()->getLocation()->getRow()}",
				'to' => "{$move->getTo->getLocation()->getColumn()},{$move->getTo()->getLocation()->getRow()}",
				'piece'	=> "{$move->getChessPiece()->getType()},{$move->getChessPiece()->getColor()}",
			);
		}
		
		
		
		if ($engine->getPlayerWhoseTurnIsNow()->getId() == $user_id)
		{
			$response_array['is_your_turn'] = true;
		}
		else
		{
			$response_array['is_your_turn'] = false;
		}
		
		$this->layout = false;
		
		$this->render("game");
		
	}
	
}