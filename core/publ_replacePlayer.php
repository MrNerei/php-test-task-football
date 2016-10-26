<?php
    namespace NH\footballParser\publication;

    class replacePlayer extends publication
    {
        const REPLACE_PLAYER_TEMPLATE_PATH = "templates/block-replace.tpl";

        public function do_print()
        {
            ob_start();
            include self::REPLACE_PLAYER_TEMPLATE_PATH;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }