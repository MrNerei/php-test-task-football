<?php
    namespace NH\footballParser\publication;

    class goal extends publication
    {
        const GOAL_TEMPLATE_PATH = "templates/block-goal.tpl";

        public function do_print()
        {
            ob_start();
            include self::GOAL_TEMPLATE_PATH;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }