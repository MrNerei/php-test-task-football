<div class="col-xs-12">
	<div class="info replace">
		<div class="row">
			<div class="col-xs-12 col-lg-2 imgwrap">
				<img src="../images/replace.png" alt="Replacement">
			</div>	
			<div class="col-xs-8">
				<p class="desc">
					<?=$this->description;?>
				</p>
				<p class="team">Команда: <?=$this->details->team;?></p>
				<p class="team">Заменяемый: <?=$this->details->outPlayerNumber;?></p>
				<p class="team">Заменяющий: <?=$this->details->inPlayerNumber;?></p>
			</div>
			<div class="col-xs-12 col-lg-2 timewrap">
				<p class="time">
					<?=$this->time;?>'
				</p>
			</div>	
		</div>
	</div>
</div>