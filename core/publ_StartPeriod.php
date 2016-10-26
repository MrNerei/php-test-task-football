<?php
namespace NH\footballParser\publication;

class startPeriod extends publication
{
    const START_PERIOD_TEMPLATE_PATH = "templates/block-start.tpl";

    public function do_print()
    {
        ob_start();
        include self::START_PERIOD_TEMPLATE_PATH;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}