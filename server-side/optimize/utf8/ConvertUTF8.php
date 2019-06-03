<?php
    namespace optimize\utf8;

    class ConvertUTF8 {
        public function __construct() {

        }

        // encode utf8 format for data
        public function utf8ize($mixed) {
            if (is_array($mixed)) {
                foreach ($mixed as $key => $value) {
                    $mixed[$key] = $this->utf8ize($value);
                }
            } else if (is_string ($mixed)) {
                return utf8_encode($mixed);
            }
            return $mixed;
        }
    }