<?php
namespace NH\footballParser {

    use NH\footballParser\config as config;
    use NH\footballParser\jsonFile\jsonFile;
    use NH\footballParser\publication as publication;

    require_once "core/config.php";
    require_once "core/jsonFile.php";
    require_once "core/publication.php";

    require_once "core/publ_Info.php";
    require_once "core/publ_finishPeriod.php";
    require_once "core/publ_StartPeriod.php";
    require_once "core/publ_goal.php";
    require_once "core/publ_redCard.php";
    require_once "core/publ_yellowCard.php";
    require_once "core/publ_DangerousMoment.php";
    require_once "core/publ_replacePlayer.php";

    $config = new config\config('source/matches');

    /** @var $file jsonFile */
    foreach ($config->getFilesList() as $file) {
        $file->parseJson();
        $content = "";
        foreach ($file->getData() as $note) {
            if (!($note instanceof publication\publication)) {
                throw new \Exception("Ошибка! Неопознанный класс! Класс должен быть наследником абстрактного класа publication!");
            }
            $content .= $note->do_print();
        }

        $header = file_get_contents("templates/header.tpl");
        $footer = file_get_contents("templates/footer.tpl");
        ob_start();
        include "templates/statistics.tpl";
        $statBlock = ob_get_contents();
        ob_end_clean();

        $res_file = fopen("result/" . $file->getTitle() . ".html", "w");
        fwrite($res_file, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fwrite($res_file, $header);
        fwrite($res_file, $statBlock);
        fwrite($res_file, $content);
        fwrite($res_file, $footer);

        fclose($res_file);
    }
}