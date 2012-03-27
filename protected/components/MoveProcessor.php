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
		
		if ($sourceSquare->equal($destinationSquare))
		{
			return self::ERROR_INVALID_MOVEMENT;
		}
		
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
	
		
		if ($specialMovement)
		{
			if ($specialMovement == \Enums\SpecialMovement::CASTLING)
			{
				if ($sourceSquare->getLocation()->getColumn() > $destinationSquare->getLocation()->getColumn())
				{
					// King is moving to the left

					$newKingLocation	= $game->getChessBoard()->getSquareByLocation(
							new \Libs\Coordinates($sourceSquare->getLocation()->getRow(), 'c')
					);

					$newRookLocation	= $game->getChessBoard()->getSquareByLocation(
							new \Libs\Coordinates($sourceSquare->getLocation()->getRow(), 'd')
					);

					$castleType = "castle-queenSide";
				}
				else
				{
					// King is moving to the left

					$newKingLocation	= $game->getChessBoard()->getSquareByLocation(
							new \Libs\Coordinates($sourceSquare->getLocation()->getRow(), 'g')
					);

					$newRookLocation	= $game->getChessBoard()->getSquareByLocation(
							new \Libs\Coordinates($sourceSquare->getLocation()->getRow(), 'f')
					);

					$castleType = "castle-kingSide";
				}

				$king = $sourceSquare->getChessPiece();
				$rook = $destinationSquare->getChessPiece();

				$sourceSquare->setChessPiece(null);
				$destinationSquare->setChessPiece(null);

				$newKingLocation->setChessPiece($king);
				$newRookLocation->setChessPiece($rook);

				$game->addMovement(new Libs\Movement($sourceSquare, $newKingLocation, $castleType));
			}
			else if ($specialMovement == \Enums\SpecialMovement::PROMOTION)
			{
				$destinationSquare->setChessPiece(
						new \Libs\ChessPiece(\Enums\ChessPieceType::QUEEN, $engine->getPlayerWhoseTurnIsNow()->getColor())
				);

				$sourceSquare->setChessPiece(null);

				$game->addMovement(new Libs\Movement($sourceSquare, $destinationSquare, \Enums\SpecialMovement::PROMOTION));

			}
		}
		else
		{
			$sourceSquare->setChessPiece(null);
			$destinationSquare->setChessPiece($movingPiece);
			
			$game->addMovement(new \Libs\Movement($sourceSquare, $destinationSquare));
		}
		
		
		
//		var_dump($destinationSquare);
		
		
		
//		var_dump($gameModel->Data->getAllMovements());
//		die();
		
		
		return self::NO_ERROR;
		
	}
}