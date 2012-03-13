<?php
$this->breadcrumbs = array(
	'Dashboard',
);
?>


<div style="text-align: center;">
	<h2>
		Start New Game
	</h2>
</div>


<form name="new_game" method="POST" action="">
	<label>
		Game Type:
	</label>

	<select name="game_type" onchange="if($(this).val() == 2) { $('#ai_skill').show(); } else { $('#ai_skill').hide(); }">
		<option value="0">Game Type</option>
		<option value="1">Player VS Player</option>
		<option value="2">Player VS Computer</option>
		<option value="3">Computer VS Computer</option>
	</select>

	<div id="ai_skill" style="display:none;">
		<label>Computer Skill: </label>

		<select name="skill">
			<option value="1">Dumb</option>
			<option value="5">Easy</option>
			<option value="10">Medium</option>
			<option value="15">Hard</option>
			<option value="20">Brutal</option>
		</select>
	</div>
	<br/>

	<!--
	
	<h3>Time Limits</h3>
	
	<label>White player:</label>
	<input type="text" name="wtime" value="0" size="3"/> min.
	
	<br/>
	
	<label>Black player:</label>
	<input type="text" name="btime" value="0" size="3" /> min.
	-->

	<br/>
	<br/>

	<button type="submit">Start new game !</button>
</form>