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
		
	}
	
}