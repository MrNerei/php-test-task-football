<?php
    namespace NH\footballParser\publication;

    class redCard extends publication
    {
        const RED_CARD_TEMPLATE_PATH = "templates/block-red.tpl";

        public function do_print()
        {
            ob_start();
            include self::RED_CARD_TEMPLATE_PATH;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }