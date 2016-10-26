<?php
    namespace NH\footballParser\publication;

    class yellowCard extends publication
    {
        const YELLOW_CARD_TEMPLATE_PATH = "templates/block-yellow.tpl";

        public function do_print()
        {
            ob_start();
            include self::YELLOW_CARD_TEMPLATE_PATH;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }