<?php

class MoveProcessor extends CComponent
{
	const NO_ERROR = 1;
	
	const ERROR_INVALID_MOVEMENT = 2;
	const ERROR_NOT_YOUR_TURN = 3;
	const ERROR_CHECKMATE = 4;
	const ERROR_GAME_FINISHED = 5;
	
	public function process(MoveForm $move, Game $gameModel, $ai_turn = false)
	{
		$game = $gameModel->Data;
		$engine = new \Libs\GameEngine($game);
		
		if ($game->isGameFinished())
		{
			return self::ERROR_GAME_FINISHED;
		}
		
		if ($ai_turn == false && $engine->getPlayerWhoseTurnIsNow()->getId() != Yii::app()->user->id)
		{
			return self::ERROR_NOT_YOUR_TURN;
		}
		
		
		list($moveFromRow, $moveFromColumn, $moveToRow, $moveToColumn) = 
				array($move->from[1], $move->from[0], $move->to[1], $move->to[0]);
		
		
		
		$sourceSquare = $game->getChessBoard()->getSquareByLocation(new \Libs\Coordinates($moveFromRow, $moveFromColumn));
		$destinationSquare = $game->getChessBoard()->getSquareByLocation(new \Libs\Coordinates($moveToRow, $moveToColumn));
		
		// If there's no chess piece on the source square -- Movement isn't allowed ffs
		if (is_null($sourceSquare->getChessPiece()))
		{
			Yii::log("[Game ID: $gameModel->id] Requested empty move from $sourceSquare to $destinationSquare");
			
			return self::ERROR_INVALID_MOVEMENT;
		}
		
		$specialMovement = false; // Castling, promotion, etc.
		
		if ( ! $engine->isMovementAllowed($sourceSquare, $destinationSquare, $specialMovement))
		{
			return self::ERROR_INVALID_MOVEMENT;
		}
		
		$movingPiece = $sourceSquare->getChessPiece();
		$movingPiece->increaseMovements();
		
		
		
		$sourceSquare->setChessPiece(null);
		$destinationSquare->setChessPiece($movingPiece);
		
//		var_dump($destinationSquare);
		
		$game->addMovement(new \Libs\Movement($sourceSquare, $destinationSquare));
		
//		var_dump($gameModel->Data->getAllMovements());
//		die();
		
		
		return self::NO_ERROR;
		
	}
}