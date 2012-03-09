<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<script type="text/javascript" src="js/jquery.js"></script>
		<link rel="stylesheet" href="css/board.css" type="text/css" />
	</head>

	<body>

		<table id="chess_board" cellpadding="0" cellspacing="0">
			<tr>
			<?php foreach (range('A', 'H') AS $column): ?>
			
				<td><strong><?php echo $column; ?></strong></td>
			
			<?php endforeach; ?>
			</tr>
			
			<?php /* for ($row = 1; $row <=8; $row++): ?>
			<tr>
				
				<?php foreach (range('A', 'H') AS $column): ?>
				<td id="<?php echo $column.$row ?>"></td>
				<?php endforeach; ?>
				
			</tr>
			
			<?php endfor; */?>
			
			
			<?php /* @var $game \Libs\ChessGame */ /* @var $drawHelper \Libs\SimpleDrawHelper */
			foreach ($game->getChessBoard()->getBoardMatrix() AS $row): ?>
			<tr>
				<?php foreach ($row AS $column): /* @var $column \Libs\ChessBoardSquare */ ?>
				<td id="<?php echo strtoupper($column->getLocation()->getColumn()).$column->getLocation()->getRow() ?>">
					
					<?php if ($column->getChessPiece()): ?>
					
					<a href="#" class="<?php echo $column->getChessPiece()->getType() . " " . $column->getChessPiece()->getColor(); ?>">
						<?php echo $drawHelper->getChessPieceSymbol($column->getChessPiece());  ?>
					</a>
	
	
					<?php endif;?>
				</td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			<!--
			
			<tr>
				<td id="A8"><a href="#" class="rook black">&#9820;</a></td>
				<td id="B8"><a href="#" class="night black">&#9822;</a></td>
				<td id="C8"><a href="#" class="bishop black">&#9821;</a></td>
				<td id="D8"><a href="#" class="king black">&#9819;</a></td>
				<td id="E8"><a href="#" class="queen black">&#9818;</a></td>
				<td id="F8"><a href="#" class="bishop black">&#9821;</a></td>
				<td id="G8"><a href="#" class="night black">&#9822;</a></td>
				<td id="H8"><a href="#" class="rook black">&#9820;</a></td>
			</tr>
			<tr>
				<td id="A7"><a href="#" class="pawn black">&#9823;</a></td>
				<td id="B7"><a href="#" class="pawn black">&#9823;</a></td>
				<td id="C7"><a href="#" class="pawn black">&#9823;</a></td>
				<td id="D7"><a href="#" class="pawn black">&#9823;</a></td>
				<td id="E7"><a href="#" class="pawn black">&#9823;</a></td>
				<td id="F7"><a href="#" class="pawn black">&#9823;</a></td>
				<td id="G7"><a href="#" class="pawn black">&#9823;</a></td>
				<td id="H7"><a href="#" class="pawn black">&#9823;</a></td>
			</tr>
			<tr>
				<td id="A6"></td>
				<td id="B6"></td>
				<td id="C6"></td>
				<td id="D6"></td>
				<td id="E6"></td>
				<td id="F6"></td>
				<td id="G6"></td>
				<td id="H6"></td>
			</tr>
			<tr>
				<td id="A5"></td>
				<td id="B5"></td>
				<td id="C5"></td>
				<td id="D5"></td>
				<td id="E5"></td>
				<td id="F5"></td>
				<td id="G5"></td>
				<td id="H5"></td>
			</tr>
			<tr>
				<td id="A4"></td>
				<td id="B4"></td>
				<td id="C4"></td>
				<td id="D4"></td>
				<td id="E4"></td>
				<td id="F4"></td>
				<td id="G4"></td>
				<td id="H4"></td>
			</tr>
			<tr>
				<td id="A3"></td>
				<td id="B3"></td>
				<td id="C3"></td>
				<td id="D3"></td>
				<td id="E3"></td>
				<td id="F3"></td>
				<td id="G3"></td>
				<td id="H3"></td>
			</tr>
			<tr>
				<td id="A2"><a href="#" class="pawn white">&#9817;</a></td>
				<td id="B2"><a href="#" class="pawn white">&#9817;</a></td>
				<td id="C2"><a href="#" class="pawn white">&#9817;</a></td>
				<td id="D2"><a href="#" class="pawn white">&#9817;</a></td>
				<td id="E2"><a href="#" class="pawn white">&#9817;</a></td>
				<td id="F2"><a href="#" class="pawn white">&#9817;</a></td>
				<td id="G2"><a href="#" class="pawn white">&#9817;</a></td>
				<td id="H2"><a href="#" class="pawn white">&#9817;</a></td>
			</tr>
			<tr>
				<td id="A1"><a href="#" class="rook white">&#9814;</a></td>
				<td id="B1"><a href="#" class="night white">&#9816;</a></td>
				<td id="C1"><a href="#" class="bishop white">&#9815;</a></td>
				<td id="D1"><a href="#" class="king white">&#9813;</a></td>
				<td id="E1"><a href="#" class="wife white">&#9812;</a></td>
				<td id="F1"><a href="#" class="bishop white">&#9815;</a></td>
				<td id="G1"><a href="#" class="night white">&#9816;</a></td>
				<td id="H1"><a href="#" class="rook white">&#9814;</a></td>
			</tr>
			-->
			
			<tr>
			<?php foreach (range('A', 'H') AS $column): ?>
			
				<td><strong><?php echo $column; ?></strong></td>
			
			<?php endforeach; ?>
			</tr>
		</table>

	</body>

</html>

