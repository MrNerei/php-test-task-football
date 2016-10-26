<?php
//namespace NH\footballParser{

//    use NH\footballParser\config as config;

    require_once "core/config.php";
    require_once "core/jsonFile.php";
    require_once "core/publication.php";

    $config = new config('source/matches');

    foreach($config->getFilesList() as $file)
    {
        $file->parseJson();
        $content = "";
        foreach ($file->getData() as $note)
        {
            if ($note instanceof publication) {
                $content .= $note->do_print();
            } else {
                throw new \Exception("Ошибка! Неопознанный класс! Класс должен быть наследником абстрактного класа publication!");
            }
        }

        $header = file_get_contents("templates/header.tpl");
        $footer = file_get_contents("templates/footer.tpl");
        ob_start();
        include "templates/statistics.tpl";
        $statBlock = ob_get_contents();
        ob_end_clean();

        $res_file = fopen("result/" . $file->getTitle() . ".html", "w");
        fwrite($res_file,  chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($res_file, $header);
        fwrite($res_file, $statBlock);
        fwrite($res_file, $content);
        fwrite($res_file, $footer);

        fclose($res_file);
    }
