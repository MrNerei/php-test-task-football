<?php
	

	class config{

		public static $dir = 'source/matches';
		public static $files_list;


		public static function check_dir($dir){
			$files = scandir($dir);
			unset($files[array_search('.', $files)]);
			unset($files[array_search('..', $files)]);
			if (substr($dir, -1) != "/"){
				$dir=$dir."/";
			}
			foreach ($files as &$file){
				if  (pathinfo($file, PATHINFO_EXTENSION) == "json"){
					$file = new json_file($dir, $file);
				}
			}
			self::$files_list = $files;
		}

	}


	class json_file{

		public $file_name;
		public $title;
		public $data = array();

		public $gametime;
		public $result_stat=array();
		public $events_stat=array();
		public $players_stat=array();


		function __construct($dir, $file) {
			$this->file_name = $dir.$file;
			$this->title = pathinfo($file, PATHINFO_FILENAME);
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
  						$this->set_yellow($inf_obj);
  					break;
  					case "redCard":
  						$this->data[] = new redCard($inf_obj);
  						$this->set_red($inf_obj);
  					break;
					case "goal":
						$this->data[] = new goal($inf_obj);
						$this->set_goal($inf_obj);
					break;
					case "finishPeriod":
						$this->data[] = new finishPeriod($inf_obj);
						$this->gametime=$inf_obj->time;
					break;
					case "replacePlayer":
						$this->data[] = new replacePlayer($inf_obj);
						$this->set_replace_stat($inf_obj);
					break;
					default: 
						$this->data[] = new info($inf_obj);
					break;
				}
			}
		}

		public function get_name($team, $num){
			return $this->players_stat[$team][$num]["name"];
		}

		protected function set_base_info($inf){
			if (count($inf->details)>0){
				if (count($this->result_stat)==0){
					$this->result_stat = array($inf->details->team1->title => 0, $inf->details->team2->title => 0);
				}

				$t1p=array();
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
						$player["startTime"]=0;
					}
				}
				foreach ($this->players_stat[$inf->details->team2->title] as $number=>&$player){
					if (in_array($number, $inf->details->team2->startPlayerNumbers)){
						$player["startTime"]=0;
					}
				}
			}
		}

		protected function set_goal($inf){
			$this->result_stat[$inf->details->team]++;
			if (isset($this->players_stat[$inf->details->team][$inf->details->playerNumber]["goals"])){
				$this->players_stat[$inf->details->team][$inf->details->playerNumber]["goals"]++;
			} else {
				$this->players_stat[$inf->details->team][$inf->details->playerNumber]["goals"]=1;
			}

			if (($inf->details->assistantNumber!="") and ($inf->details->assistantNumber!=null)){
				if (isset($this->players_stat[$inf->details->team][$inf->details->assistantNumber]["assists"])){
					$this->players_stat[$inf->details->team][$inf->details->assistantNumber]["assists"]++;
				} else {
					$this->players_stat[$inf->details->team][$inf->details->assistantNumber]["assists"]=1;
				}
			}

			$this->events_stat[$inf->time]=array("type"=> "goal", "team"=>$inf->details->team, "player"=>$inf->details->playerNumber, "assist"=>$inf->details->assistantNumber);
		}

		protected function set_yellow($inf){

			if (isset($this->players_stat[$inf->details->team][$inf->details->playerNumber]["yellow"])){
				$this->players_stat[$inf->details->team][$inf->details->playerNumber]["yellow"]++;
				$this->players_stat[$inf->details->team][$inf->details->playerNumber]["finTime"]=$inf->time;
				$this->events_stat[$inf->time]=array("type"=> "yellow", "team"=>$inf->details->team, "player"=>$inf->details->playerNumber);
				$this->events_stat[$inf->time]=array("type"=> "remove", "team"=>$inf->details->team, "player"=>$inf->details->playerNumber);
			} else {
				$this->players_stat[$inf->details->team][$inf->details->playerNumber]["yellow"]=1;
				$this->events_stat[$inf->time]=array("type"=> "yellow", "team"=>$inf->details->team, "player"=>$inf->details->playerNumber);
			}
		}

		protected function set_red($inf){

			$this->players_stat[$inf->details->team][$inf->details->playerNumber]["red"]=1;
			$this->players_stat[$inf->details->team][$inf->details->playerNumber]["finTime"]=$inf->time;
			$this->events_stat[$inf->time]=array("type"=> "red", "team"=>$inf->details->team, "player"=>$inf->details->playerNumber);
			$this->events_stat[$inf->time]=array("type"=> "remove", "team"=>$inf->details->team, "player"=>$inf->details->playerNumber);
		}

		protected function set_replace_stat($inf){

			$this->players_stat[$inf->details->team][$inf->details->inPlayerNumber]["startTime"] = $inf->time;
			$this->players_stat[$inf->details->team][$inf->details->outPlayerNumber]["finTime"]=$inf->time;

			$this->events_stat[$inf->time]=array("type"=> "replace", "team"=>$inf->details->team, "playerIn"=>$inf->details->inPlayerNumber, "playerOut"=>$inf->details->outPlayerNumber);
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
 			ob_start();
 			include "templates/block-info.tpl";
 			$content = ob_get_contents();
			ob_end_clean();
  			return $content;
 		}
 	}


 	class startPeriod extends publication {
 		public function do_print() {
  			ob_start();
 			include "templates/block-start.tpl";
 			$content = ob_get_contents();
			ob_end_clean();
  			return $content;
 		}
 	}


 	class finishPeriod extends publication {
 		public function do_print() {
  			ob_start();
 			include "templates/block-end.tpl";
 			$content = ob_get_contents();
			ob_end_clean();
  			return $content;
 		}
 	}
	

	class dangerousMoment extends publication {
 		public function do_print() {
  			ob_start();
 			include "templates/block-dangerous.tpl";
 			$content = ob_get_contents();
			ob_end_clean();
  			return $content;
 		}
 	}


 	class yellowCard extends publication {
 		public function do_print() {
  			ob_start();
 			include "templates/block-yellow.tpl";
 			$content = ob_get_contents();
			ob_end_clean();
  			return $content;
 		}
 	}


 	class redCard extends publication {
 		public function do_print() {
  			ob_start();
 			include "templates/block-red.tpl";
 			$content = ob_get_contents();
			ob_end_clean();
  			return $content;
 		}
 	}


 	class goal extends publication {
 		public function do_print() {
  			ob_start();
 			include "templates/block-goal.tpl";
 			$content = ob_get_contents();
			ob_end_clean();
  			return $content;
 		}
 	}


 	class replacePlayer extends publication {
 		public function do_print() {
  			ob_start();
 			include "templates/block-replace.tpl";
 			$content = ob_get_contents();
			ob_end_clean();
  			return $content;
 		}
 	}

	
	//Типы json наборов данных
	/*
	"info"
	"startPeriod"
	"dangerousMoment"
  	"yellowCard"
  	//"redCard"
	"goal"
	"finishPeriod"
	"replacePlayer"
	*/


	config::check_dir(config::$dir);

	foreach(config::$files_list as $file){
		$content = "";

		$file->parse_json();
		foreach($file->data as $note){
			if ($note instanceof publication) {
				$content.= $note->do_print();
			}
			else {
				echo "Ошибка! Неопознанный класс!";
			}
		}

		$header = file_get_contents("templates/header.tpl");
		$footer = file_get_contents("templates/footer.tpl");
		ob_start();
 		include "templates/statistics.tpl";
 		$statblock = ob_get_contents();
		ob_end_clean();


		$res_file = fopen("result/".$file->title.".html", "w");
		fwrite($res_file, $header);
		fwrite($res_file, $statblock);
		fwrite($res_file, $content);
		fwrite($res_file, $footer);

		fclose($res_file);
	}


?>