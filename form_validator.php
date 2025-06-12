<?php
    class FormValidator {
        public $regex_arr;
        public $max_length_arr;
        public $min_length_arr;
        public $keys;
        public $targets;

        public function __construct($regex_arr, $max_length_arr, $min_length_arr, $keys, $targets) {
            $this->regex_arr = $regex_arr;
            $this->max_length_arr = $max_length_arr;
            $this->min_length_arr = $min_length_arr;
            $this->keys = $keys;
            $this->targets = $targets;
        }

        public function validate_regex($regex_arr, $target_str) {
            foreach ($regex_arr as $regex) {
                if (preg_match($regex, $target_str)) {
                    return false;
                }
            }

            return true;
        }

        public function validate_all_regex() {
            for ($i = 0; $i < count($this->keys); $i++) {
                $key = $this->keys[$i];
                $target = $this->targets[$i];
                if (!$this->validate_regex($this->regex_arr[$key], $target)) {
                    return $target;
                }
            }

            return true;
        }

        public function validate_all_length($type) {
            for ($i = 0; $i < count($this->keys); $i++) {
                $key = $this->keys[$i];
                $target = $this->targets[$i];
                if ($type == "max" ? strlen($target) > $this->max_length_arr[$key] :  strlen($target) < $this->min_length_arr[$key] ) {
                    return $target;
                }
            }

            return true;
        }
        
    }
?>