<?php
    namespace NH\footballParser\publication;

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