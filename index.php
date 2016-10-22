<?php
	

	class config{

		public static $dir = 'source/matches';
		public static $files_list;

		public static function check_dir($dir){
			$files = scandir($dir);
			unset($files[array_search('.', $files)]);
			unset($files[array_search('..', $files)]);
//			print_r($files);
			if (substr($dir, -1) != "/"){
				$dir=$dir."/";
			}
			foreach ($files as &$file){
				if  (pathinfo($file, PATHINFO_EXTENSION) == "json"){
					$file = new json_file($dir.$file);
				}
			}
			self::$files_list = $files;
		}
	}

	class json_file{

		public $file_name;
		public $data = array();

		public $result_stat=array();
		public $events_stat=array();
		public $replace_stat=array();
		public $players_stat=array();
		// public $main_players_stat=array();
		// public $spare_players_stat=array();
		// public $players_time_stat=array();
		// public $goals_players_stat=array();
		// public $players_cards_stat=array();

		function __construct($file_name) {
			$this->file_name = $file_name;
		}

		public function parse_json(){
			$json = file_get_contents($this->file_name, true);
			$info = json_decode($json);
			$control=array();
			foreach($info as $inf_obj)
			{
				switch ($inf_obj->type){
					case "info":
						$this->data[] = new info($inf_obj);
					break;
					case "startPeriod":
						$this->data[] = new startPeriod($inf_obj);
						$this->set_base_info($inf_obj);
					break;
					case "dangerousMoment":
						$this->data[] = new dangerousMoment($inf_obj);
					break;
  					case "yellowCard":
  						$this->data[] = new yellowCard($inf_obj);
  					break;
  					case "redCard":
  						$this->data[] = new redCard($inf_obj);
  					break;
					case "goal":
						$this->data[] = new goal($inf_obj);
						$this->set_goal($inf_obj);
					break;
					case "finishPeriod":
						$this->data[] = new finishPeriod($inf_obj);
					break;
					case "replacePlayer":
						$this->data[] = new replacePlayer($inf_obj);
					break;
					default: 
						$this->data[] = new info($inf_obj);
					break;
				}
			}
//				print_r($this->data);
		}

		protected function set_base_info($inf){

			if (count($inf->details)>0){

				if (count($this->result_stat)==0){
					$this->result_stat = array($inf->details->team1->title => 0, $inf->details->team2->title => 0);
				}

				$t1p=array(); // массивы ключ-значение более удобны для последующей обработки
				foreach ($inf->details->team1->players as $player){
					$t1p[$player->number]["name"] = $player->name;
				}
				$t2p=array();
				foreach ($inf->details->team2->players as $player){
					$t2p[$player->number]["name"] = $player->name;
				}

				$this->players_stat = array($inf->details->team1->title => $t1p, $inf->details->team2->title => $t2p);

				foreach ($this->players_stat[$inf->details->team1->title] as $number=>&$player){
					if (in_array($number, $inf->details->team1->startPlayerNumbers)){
						$player["startTime"]=$inf->time;
					}
				}
				foreach ($this->players_stat[$inf->details->team2->title] as $number=>&$player){
					if (in_array($number, $inf->details->team2->startPlayerNumbers)){
						$player["startTime"]=$inf->time;
					}
				}
//				echo "<pre>"; print_r($this->result_stat); echo "</pre>";
			}
		}
		protected function set_goal($inf){

			$this->result_stat[$inf->details->team]++;

			if (isset($this->players_stat[$inf->details->team][$inf->details->playerNumber]["goals"])){
				$this->players_stat[$inf->details->team][$inf->details->playerNumber]["goals"]++;
			} else {
				$this->players_stat[$inf->details->team][$inf->details->playerNumber]["goals"]=1;
			}

			if (isset($this->players_stat[$inf->details->team][$inf->details->assistantNumber]["assists"])){
				$this->players_stat[$inf->details->team][$inf->details->assistantNumber]["assists"]++;
			} else {
				$this->players_stat[$inf->details->team][$inf->details->assistantNumber]["assists"]=1;
			}
		}
		protected function set_events_stat($inf){

		}
		protected function set_main_players_stat($inf){

		}
		protected function set_replace_stat($inf){

		}
		protected function set_spare_players_stat($inf){

		}
		protected function set_players_time_stat($inf){

		}
		protected function set_goals_players_stat($inf){

		}
		protected function set_players_cards_stat($inf){

		}

	}
	

	abstract class publication {

		public $time;
		public $description;
		public $details;

		abstract public function do_print();

		function __construct($inf_obj) {
			$this->time = $inf_obj->time;
			$this->description = $inf_obj->description;
			$this->details = $inf_obj->details;
		}
	}


	class info extends publication {
 		public function do_print() {
  			echo '<h2>Общие сведения</h2>';
  			echo '<p>'.$this->time.'</p>';
 		}
 	}


 	class startPeriod extends publication {
 		public function do_print() {
  			echo '<h2>Начало периода</h2>';
  			echo '<p>'.$this->time.'</p>';
 		}
 	}


 	class finishPeriod extends publication {
 		public function do_print() {
  			echo '<h2>Конец периода</h2>';
  			echo '<p>'.$this->time.'</p>';
 		}
 	}
	

	class dangerousMoment extends publication {
 		public function do_print() {
  			echo '<h2>Опасный момент</h2>';
  			echo '<p>'.$this->time.'</p>';
 		}
 	}


 	class yellowCard extends publication {
 		public function do_print() {
  			echo '<h2>Желтая карточка</h2>';
  			echo '<p>'.$this->time.'</p>';
 		}
 	}


 	class redCard extends publication {
 		public function do_print() {
  			echo '<h2>Красная карточка</h2>';
  			echo '<p>'.$this->time.'</p>';
 		}
 	}


 	class goal extends publication {
 		public function do_print() {
  			echo '<h2>Гоооооол!!</h2>';
  			echo '<p>'.$this->time.'</p>';
 		}
 	}


 	class replacePlayer extends publication {
 		public function do_print() {
  			echo '<h2>Замена!</h2>';
  			echo '<p>'.$this->time.'</p>';
 		}
 	}

	/*
	"info"
	"startPeriod"
	"dangerousMoment"
  	"yellowCard"
	"goal"
	"finishPeriod"
	"replacePlayer"
	*/


	config::check_dir(config::$dir);

	foreach(config::$files_list as $file){
		$file->parse_json();
		foreach($file->data as $note){
			if ($note instanceof publication) {
				$note->do_print();
			}
			else {
				echo "Ошибка! Неопознанный класс!";
			}
		}

		echo "<pre>"; 
		print_r($file->result_stat); 
		echo "</pre>";

	}

//	print_r(config::$files_list);



?>