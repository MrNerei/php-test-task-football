<?php
namespace NH\footballParser\publication;

class finishPeriod extends publication
{
    const FINISH_PERIOD_TEMPLATE_PATH = "templates/block-end.tpl";

    public function do_print()
    {
        ob_start();
        include self::FINISH_PERIOD_TEMPLATE_PATH;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}