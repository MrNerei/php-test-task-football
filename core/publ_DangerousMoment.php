<?php
    namespace NH\footballParser\publication;

    class dangerousMoment extends publication
    {
        const DANGER_MOMENT_TEMPLATE_PATH = "templates/block-dangerous.tpl";

        public function do_print()
        {
            ob_start();
            include self::DANGER_MOMENT_TEMPLATE_PATH;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }