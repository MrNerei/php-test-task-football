<?php
    namespace NH\footballParser\config;

    use NH\footballParser\jsonFile as jsonFile;

    class config
    {
        private $dir = 'source/matches';
        private $filesList = [];

        public function  __construct($dir)
        {
            if (file_exists($dir)) {
                $this->dir = $dir;
            }
            $this->checkDir($this->dir);
        }

        public function getFilesList(){
            return $this->filesList;
        }

        private function checkDir($dir)
        {
            $files = scandir($dir);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) != "json") {
                    continue;
                }
                $this->filesList[] = new jsonFile\jsonFile($dir, $file);
            }
        }
    }
