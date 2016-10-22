<div class="col-xs-12">
	<div class="info red">
		<div class="row">
			<div class="col-xs-12 col-lg-2 imgwrap">
				<img src="../images/card.png" alt="Card">
			</div>	
			<div class="col-xs-8">
				<p class="desc">
					<?=$this->description;?>
				</p>
				<p class="team">Команда: <?=$this->details->team;?></p>
				<p class="team">Игрок: <?=$this->details->playerNumber;?></p>
			</div>
			<div class="col-xs-12 col-lg-2 timewrap">
				<p class="time">
					<?=$this->time;?>'
				</p>
			</div>	
		</div>
	</div>
</div>