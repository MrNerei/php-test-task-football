<?php
   // namespace NH\footballParser\jsonFile;

    //use NH\footballParser\publication as publication;

    class jsonFile
    {
        private $file_name;
        private $title;
        private $data = [];

        public $gameTime;
        public $resultStat = [];
        public $eventsStat = [];
        public $playersStat = [];

        public function __construct($dir, $file) {
            if (substr($dir, -1) != "/"){
                $dir = $dir . "/";
            }
            $this->file_name = $dir.$file;
            $this->title = pathinfo($file, PATHINFO_FILENAME);
        }

        public function getData()
        {
            return $this->data;
        }
        public function  getTitle()
        {
            return $this->title;
        }

        public function parseJson()
        {
            $json = file_get_contents($this->file_name, true);
            $info = json_decode($json);
            if (json_last_error() != JSON_ERROR_NONE ){
                throw new \Exception("Произошла ошибка при декодировании json файла " . $this->file_name . ", код ошибки: " . json_last_error() . " файл не будет преобразован");
            }
            foreach($info as $inf_obj)
            {
                switch ($inf_obj->type){
                    case "info":
                        $this->data[] = new info($inf_obj);
                        break;
                    case "startPeriod":
                        $this->data[] = new startPeriod($inf_obj);
                        $this->setBaseInfo($inf_obj);
                        break;
                    case "dangerousMoment":
                        $this->data[] = new dangerousMoment($inf_obj);
                        break;
                    case "yellowCard":
                        $this->data[] = new yellowCard($inf_obj);
                        $this->setYellowClass($inf_obj);
                        break;
                    case "redCard":
                        $this->data[] = new redCard($inf_obj);
                        $this->setRedClass($inf_obj);
                        break;
                    case "goal":
                        $this->data[] = new goal($inf_obj);
                        $this->setGoalClass($inf_obj);
                        break;
                    case "finishPeriod":
                        $this->data[] = new finishPeriod($inf_obj);
                        $this->gameTime=$inf_obj->time;
                        break;
                    case "replacePlayer":
                        $this->data[] = new replacePlayer($inf_obj);
                        $this->setReplaceStat($inf_obj);
                        break;
                    default:
                        $this->data[] = new info($inf_obj);
                        break;
                }
            }
        }

        public function getName($team, $num)
        {
            return $this->playersStat[$team][$num]["name"];
        }

        private function setBaseInfo($inf)
        {
            if (count($inf->details) == 0){
                return false;
            }
            if (count($this->resultStat) == 0){
                $this->resultStat = [$inf->details->team1->title => 0, $inf->details->team2->title => 0 ];
            }
            $t1p = [];
            foreach ($inf->details->team1->players as $player){
                $t1p[$player->number]["name"] = $player->name;
            }
            $t2p = [];
            foreach ($inf->details->team2->players as $player){
                $t2p[$player->number]["name"] = $player->name;
            }

            $this->playersStat = [$inf->details->team1->title => $t1p, $inf->details->team2->title => $t2p ];

            $player = null;
            foreach ($this->playersStat[$inf->details->team1->title] as $number => &$player) {
                if (in_array($number, $inf->details->team1->startPlayerNumbers)) {
                    $player["startTime"] = 0;
                }
            }
            foreach ($this->playersStat[$inf->details->team2->title] as $number=>&$player){
                if (in_array($number, $inf->details->team2->startPlayerNumbers)){
                    $player["startTime"]=0;
                }
            }
            return true;
        }

        private function setGoalClass($inf)
        {
            $this->resultStat[$inf->details->team]++;
            if (!(isset($this->playersStat[$inf->details->team][$inf->details->playerNumber]["goals"]))){
                $this->playersStat[$inf->details->team][$inf->details->playerNumber]["goals"] = 0;
            }
            $this->playersStat[$inf->details->team][$inf->details->playerNumber]["goals"]++;

            if (($inf->details->assistantNumber != "") and ($inf->details->assistantNumber != null)){
                if (!(isset($this->playersStat[$inf->details->team][$inf->details->assistantNumber]["assists"]))) {
                    $this->playersStat[$inf->details->team][$inf->details->assistantNumber]["assists"] = 0;
                }
                $this->playersStat[$inf->details->team][$inf->details->assistantNumber]["assists"]++;
            }
            $this->eventsStat[$inf->time] = ["type" => "goal", "team" => $inf->details->team, "player" => $inf->details->playerNumber, "assist"=>$inf->details->assistantNumber ];
        }

        private function setYellowClass($inf)
        {
            if (isset($this->playersStat[$inf->details->team][$inf->details->playerNumber]["yellow"])){
                $this->playersStat[$inf->details->team][$inf->details->playerNumber]["yellow"]++;
                $this->playersStat[$inf->details->team][$inf->details->playerNumber]["finTime"] = $inf->time;
                $this->eventsStat[$inf->time] = ["type" => "yellow", "team" => $inf->details->team, "player" => $inf->details->playerNumber ];
                $this->eventsStat[$inf->time] = ["type" => "remove", "team" => $inf->details->team, "player" => $inf->details->playerNumber ];
            } else {
                $this->playersStat[$inf->details->team][$inf->details->playerNumber]["yellow"] = 1;
                $this->eventsStat[$inf->time] = ["type" => "yellow", "team" => $inf->details->team, "player" => $inf->details->playerNumber ];
            }
        }

        private function setRedClass($inf)
        {
            $this->playersStat[$inf->details->team][$inf->details->playerNumber]["red"] = 1;
            $this->playersStat[$inf->details->team][$inf->details->playerNumber]["finTime"] = $inf->time;
            $this->eventsStat[$inf->time] = ["type" => "red", "team" => $inf->details->team, "player" => $inf->details->playerNumber ];
            $this->eventsStat[$inf->time] = ["type" => "remove", "team" => $inf->details->team, "player" => $inf->details->playerNumber ];
        }

        private function setReplaceStat($inf)
        {
            $this->playersStat[$inf->details->team][$inf->details->inPlayerNumber]["startTime"] = $inf->time;
            $this->playersStat[$inf->details->team][$inf->details->outPlayerNumber]["finTime"] = $inf->time;
            $this->eventsStat[$inf->time] = ["type" => "replace", "team"=>$inf->details->team, "playerIn" => $inf->details->inPlayerNumber, "playerOut" => $inf->details->outPlayerNumber ];
        }
    }