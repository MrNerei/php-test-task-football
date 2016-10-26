<?php
    namespace NH\footballParser\publication;

    class info extends publication
    {
        const INFO_TEMPLATE_PATH = "templates/block-info.tpl";

        public function do_print()
        {
            ob_start();
            include self::INFO_TEMPLATE_PATH;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }