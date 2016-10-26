<div class="col-xs-12">
	<div class="info stat">
		<div class="row">
			<div class="col-xs-12 imgwrap">
				<img src="../images/winner.png" alt="Winner"><br>
				<div class="col-xs-2 col-xs-offset-2">
					<p class="comand"><?php
						reset($file->resultStat);
						echo key($file->resultStat);
					?></p>
				</div>
				<div class="col-xs-2">
					<p class="count"><?=current($file->resultStat);?></p>
				</div>
				<div class="col-xs-2">
					<p class="count"><?php
						next($file->resultStat);
						echo current($file->resultStat);
					?></p>
				</div>
				<div class="col-xs-2">
					<p class="comand"><?=key($file->resultStat);?></p>
				</div>
			</div>	
			<div class="col-xs-12 events">
				<h2>Основные события матча:</h2>
				<?php
					foreach($file->eventsStat as $key=>$event){
						switch($event["type"]){
							case "yellow":
								echo "<div class='main'><p class='min'>".$key."&#39;</p>Игрок команды ".$event["team"]." ".$file->getName($event["team"], $event["player"]).", №".$event["player"]." получил желтую карточку</div>";
							break;
							case "red":
								echo "<div class='main'><p class='min'>".$key."&#39;</p>Игрок команды ".$event["team"]." ".$file->getName($event["team"], $event["player"]).", №".$event["player"]." получил красную карточку</div>";
							break;
							case "replace":
								echo "<div class='main'><p class='min'>".$key."&#39;</p>Замена в команде ".$event["team"].", вместо игрока ".$file->getName($event["team"], $event["playerOut"]).", №".$event["playerOut"]." на поле выходит ".$file->getName($event["team"], $event["playerIn"]).", №".$event["playerIn"]."</div>";
							break;
							case "goal":
								echo "<div class='main'><p class='min'>".$key."&#39;</p>Гол команды ".$event["team"]." в исполнении ".$file->getName($event["team"], $event["player"]).", №".$event["player"];
								if ($event["assist"]!=""){
									echo " с голевой передачи ".$file->getName($event["team"], $event["assist"]).", №".$event["assist"];
								}
								echo "</div>";
							break;
						}
					}
					
				?>
			</div>	
			<div class="col-xs-12">
				<h2>Основные игроки команд</h2>
			</div>
			<div class="col-xs-6 br">
				<?php
					reset($file->playersStat);
					echo "<h4>".key($file->playersStat)."</h4>";
					foreach (current($file->playersStat) as $key=>$player){
						if ((isset($player["startTime"])) and ($player["startTime"]==0)){
							echo "<div class='infplayer'><p class='name'>".$player["name"]."</p>";
							echo "<p class='pnum'><span>Номер:</span> ".$key."</p>";
							echo "<p class='gtime'><span>Время в игре:</span> ";
							if (isset($player["finTime"])){
								echo ($player["finTime"] - $player["startTime"])."</p>";
							}
							else{
								echo $file->gameTime."</p>";
							}
							if (isset($player["yellow"])){
								echo "<p class='yellow'><span>Желтые карточки:</span> ".$player["yellow"]."</p>";
							}
							if (isset($player["red"])){
								echo "<p class='red'>Красная карточка</p>";
							}
							if (isset($player["goals"])){
								echo "<p class='goals'><span>Голы:</span> ".$player["goals"]."</p>";
							}
							if (isset($player["assists"])){
								echo "<p class='assists'><span>Голевые передачи:</span> ".$player["assists"]."</p>";
							}
							echo "</div>";
						}
					}
				?>
			</div>
			<div class="col-xs-6">
				<?php
					next($file->playersStat);
					echo "<h4>".key($file->playersStat)."</h4>";
					foreach (current($file->playersStat) as $key=>$player){
						if ((isset($player["startTime"])) and ($player["startTime"]==0)){
							echo "<div class='infplayer'><p class='name'>".$player["name"]."</p>";
							echo "<p class='pnum'><span>Номер:</span> ".$key."</p>";
							echo "<p class='gtime'><span>Время в игре:</span> ";
							if (isset($player["finTime"])){
								echo ($player["finTime"] - $player["startTime"])."</p>";
							}
							else{
								echo $file->gameTime."</p>";
							}
							if (isset($player["yellow"])){
								echo "<p class='yellow'><span>Желтые карточки:</span> ".$player["yellow"]."</p>";
							}
							if (isset($player["red"])){
								echo "<p class='red'>Красная карточка</p>";
							}
							if (isset($player["goals"])){
								echo "<p class='goals'><span>Голы:</span> ".$player["goals"]."</p>";
							}
							if (isset($player["assists"])){
								echo "<p class='assists'><span>Голевые передачи:</span> ".$player["assists"]."</p>";
							}
							echo "</div>";
						}
					}
				?>
			</div>
			<div class="col-xs-12">
				<h2>Игроки, вышедшие на замену</h2>
			</div>
			<div class="col-xs-6 br">
				<?php
					reset($file->playersStat);
					echo "<h4>".key($file->playersStat)."</h4>";
					foreach (current($file->playersStat) as $key=>$player){
						if ((isset($player["startTime"])) and ($player["startTime"]>0)){
							echo "<div class='infplayer'><p class='name'>".$player["name"]."</p>";
							echo "<p class='pnum'><span>Номер:</span> ".$key."</p>";
							echo "<p class='gtime'><span>Время в игре:</span> ";
							if (isset($player["finTime"])){
								echo ($player["finTime"] - $player["startTime"])."</p>";
							}
							else{
								echo ($file->gameTime - $player["startTime"])."</p>";
							}
							if (isset($player["yellow"])){
								echo "<p class='yellow'><span>Желтые карточки:</span> ".$player["yellow"]."</p>";
							}
							if (isset($player["red"])){
								echo "<p class='red'>Красная карточка</p>";
							}
							if (isset($player["goals"])){
								echo "<p class='goals'><span>Голы:</span> ".$player["goals"]."</p>";
							}
							if (isset($player["assists"])){
								echo "<p class='assists'><span>Голевые передачи:</span> ".$player["assists"]."</p>";
							}
							echo "</div>";
						}
					}
				?>
			</div>
			<div class="col-xs-6">
				<?php
					next($file->playersStat);
					echo "<h4>".key($file->playersStat)."</h4>";
					foreach (current($file->playersStat) as $key=>$player){
						if ((isset($player["startTime"])) and ($player["startTime"]>0)){
							echo "<div class='infplayer'><p class='name'>".$player["name"]."</p>";
							echo "<p class='pnum'><span>Номер:</span> ".$key."</p>";
							echo "<p class='gtime'><span>Время в игре:</span> ";
							if (isset($player["finTime"])){
								echo ($player["finTime"] - $player["startTime"])."</p>";
							}
							else{
								echo ($file->gameTime - $player["startTime"])."</p>";
							}
							if (isset($player["yellow"])){
								echo "<p class='yellow'><span>Желтые карточки:</span> ".$player["yellow"]."</p>";
							}
							if (isset($player["red"])){
								echo "<p class='red'>Красная карточка</p>";
							}
							if (isset($player["goals"])){
								echo "<p class='goals'><span>Голы:</span> ".$player["goals"]."</p>";
							}
							if (isset($player["assists"])){
								echo "<p class='assists'><span>Голевые передачи:</span> ".$player["assists"]."</p>";
							}
							echo "</div>";
						}
					}
				?>
			</div>

			<div class="col-xs-12">
				<h2>Запасные игроки</h2>
			</div>
			<div class="col-xs-6 br">
				<?php
					reset($file->playersStat);
					echo "<h4>".key($file->playersStat)."</h4>";
					foreach (current($file->playersStat) as $key=>$player){
						if (!(isset($player["startTime"]))){
							echo "<p>".$player["name"].", №".$key."</p>";
						}
					}
				?>
			</div>
			<div class="col-xs-6">
				<?php
					next($file->playersStat);
					echo "<h4>".key($file->playersStat)."</h4>";
					foreach (current($file->playersStat) as $key=>$player){
						if (!(isset($player["startTime"]))){
							echo "<p>".$player["name"].", №".$key."</p>";
						}
					}
				?>
			</div>
		</div>
	</div>
</div>