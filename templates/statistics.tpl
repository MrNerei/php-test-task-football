<div class="col-xs-12">
	<div class="info stat">
		<div class="row">
			<div class="col-xs-12 imgwrap">
				<img src="../images/winner.png" alt="Winner"><br>
				<div class="col-xs-2 col-xs-offset-2">
					<p class="comand"><?
						reset($file->result_stat);
						echo key($file->result_stat);
					?></p>
				</div>
				<div class="col-xs-2">
					<p class="count"><?=current($file->result_stat);?></p>
				</div>
				<div class="col-xs-2">
					<p class="count"><?
						next($file->result_stat);
						echo current($file->result_stat);
					?></p>
				</div>
				<div class="col-xs-2">
					<p class="comand"><?=key($file->result_stat);?></p>
				</div>
			</div>	
			<div class="col-xs-12 events">
				<h2>Основные события матча:</h2>
				<?
					foreach($file->events_stat as $key=>$event){
						switch($event["type"]){
							case "yellow":
								echo "<div class='main'><p class='min'>".$key."&#39;</p>Игрок команды ".$event["team"]." ".$file->get_name($event["team"], $event["player"]).", №".$event["player"]." получил желтую карточку</div>";
							break;
							case "red":
								echo "<div class='main'><p class='min'>".$key."&#39;</p>Игрок команды ".$event["team"]." ".$file->get_name($event["team"], $event["player"]).", №".$event["player"]." получил красную карточку</div>";
							break;
							case "replace":
								echo "<div class='main'><p class='min'>".$key."&#39;</p>Замена в команде ".$event["team"].", вместо игрока ".$file->get_name($event["team"], $event["playerOut"]).", №".$event["playerOut"]." на поле выходит ".$file->get_name($event["team"], $event["playerIn"]).", №".$event["playerIn"]."</div>";
							break;
							case "goal":
								echo "<div class='main'><p class='min'>".$key."&#39;</p>Гол команды ".$event["team"]." в исполнении ".$file->get_name($event["team"], $event["player"]).", №".$event["player"];
								if ($event["assist"]!=""){
									echo " с голевой передачи ".$file->get_name($event["team"], $event["assist"]).", №".$event["assist"];
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
				<?
					reset($file->players_stat);
					echo "<h4>".key($file->players_stat)."</h4>";
					foreach (current($file->players_stat) as $key=>$player){
						if ((isset($player["startTime"])) and ($player["startTime"]==0)){
							echo "<div class='infplayer'><p class='name'>".$player["name"]."</p>";
							echo "<p class='pnum'><span>Номер:</span> ".$key."</p>";
							echo "<p class='gtime'><span>Время в игре:</span> ";
							if (isset($player["finTime"])){
								echo ($player["finTime"] - $player["startTime"])."</p>";
							}
							else{
								echo $file->gametime."</p>";
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
				<?
					next($file->players_stat);
					echo "<h4>".key($file->players_stat)."</h4>";
					foreach (current($file->players_stat) as $key=>$player){
						if ((isset($player["startTime"])) and ($player["startTime"]==0)){
							echo "<div class='infplayer'><p class='name'>".$player["name"]."</p>";
							echo "<p class='pnum'><span>Номер:</span> ".$key."</p>";
							echo "<p class='gtime'><span>Время в игре:</span> ";
							if (isset($player["finTime"])){
								echo ($player["finTime"] - $player["startTime"])."</p>";
							}
							else{
								echo $file->gametime."</p>";
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
				<?
					reset($file->players_stat);
					echo "<h4>".key($file->players_stat)."</h4>";
					foreach (current($file->players_stat) as $key=>$player){
						if ((isset($player["startTime"])) and ($player["startTime"]>0)){
							echo "<div class='infplayer'><p class='name'>".$player["name"]."</p>";
							echo "<p class='pnum'><span>Номер:</span> ".$key."</p>";
							echo "<p class='gtime'><span>Время в игре:</span> ";
							if (isset($player["finTime"])){
								echo ($player["finTime"] - $player["startTime"])."</p>";
							}
							else{
								echo ($file->gametime - $player["startTime"])."</p>";
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
				<?
					next($file->players_stat);
					echo "<h4>".key($file->players_stat)."</h4>";
					foreach (current($file->players_stat) as $key=>$player){
						if ((isset($player["startTime"])) and ($player["startTime"]>0)){
							echo "<div class='infplayer'><p class='name'>".$player["name"]."</p>";
							echo "<p class='pnum'><span>Номер:</span> ".$key."</p>";
							echo "<p class='gtime'><span>Время в игре:</span> ";
							if (isset($player["finTime"])){
								echo ($player["finTime"] - $player["startTime"])."</p>";
							}
							else{
								echo ($file->gametime - $player["startTime"])."</p>";
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
				<?
					reset($file->players_stat);
					echo "<h4>".key($file->players_stat)."</h4>";
					foreach (current($file->players_stat) as $key=>$player){
						if (!(isset($player["startTime"]))){
							echo "<p>".$player["name"].", №".$key."</p>";
						}
					}
				?>
			</div>
			<div class="col-xs-6">
				<?
					next($file->players_stat);
					echo "<h4>".key($file->players_stat)."</h4>";
					foreach (current($file->players_stat) as $key=>$player){
						if (!(isset($player["startTime"]))){
							echo "<p>".$player["name"].", №".$key."</p>";
						}
					}
				?>
			</div>
		</div>
	</div>
</div>