<div class="col-xs-12">
	<div class="info goal">
		<div class="row">
			<div class="col-xs-12 col-lg-2 imgwrap">
				<img src="../images/goal.png" alt="Goal!!!">
			</div>	
			<div class="col-xs-8">
				<p class="desc">
					<?=$this->description;?>
				</p>
				<p class="team">Команда: <?=$this->details->team;?></p>
				<p class="team">Игрок: <?=$this->details->playerNumber;?></p>
				<p class="team">Передача: <?=$this->details->assistantNumber;?></p>
			</div>
			<div class="col-xs-12 col-lg-2 timewrap">
				<p class="time">
					<?=$this->time;?>'
				</p>
			</div>	
		</div>
	</div>
</div>