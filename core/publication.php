<?php
    //namespace NH\footballParser\publication;

    abstract class publication
    {
        protected $time;
        protected $description;
        protected $details;

        abstract public function do_print();

        public function __construct($inf_obj) {
            $this->time = $inf_obj->time;
            $this->description = $inf_obj->description;
            $this->details = $inf_obj->details;
        }
    }

    class info extends publication
    {
        public function do_print()
        {
            ob_start();
            include "templates/block-info.tpl";
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    class startPeriod extends publication
    {
        public function do_print()
        {
            ob_start();
            include "templates/block-start.tpl";
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    class finishPeriod extends publication
    {
        public function do_print()
        {
            ob_start();
            include "templates/block-end.tpl";
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    class dangerousMoment extends publication
    {
        public function do_print()
        {
            ob_start();
            include "templates/block-dangerous.tpl";
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    class yellowCard extends publication
    {
        public function do_print()
        {
            ob_start();
            include "templates/block-yellow.tpl";
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    class redCard extends publication
    {
        public function do_print()
        {
            ob_start();
            include "templates/block-red.tpl";
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    class goal extends publication
    {
        public function do_print()
        {
            ob_start();
            include "templates/block-goal.tpl";
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    class replacePlayer extends publication
    {
        public function do_print()
        {
            ob_start();
            include "templates/block-replace.tpl";
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }