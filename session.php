<?php 
    class Session {
        public $key;
        public $Database;

        public function __construct($key, $Database) {
            $this->key = $key;
            $this->Database = $Database;
        }

        public function login_user($session_data) {
            $_SESSION[$this->key] = $session_data;
        }

        public function user_logged_in($redirect_path) {
            isset($_SESSION[$this->key]) ? header("Location: $redirect_path") : null;
        }

        public function logout_user($table_name, $values, $condition) {
            unset($_SESSION[$this->key]);
            $this->Database->update_table($table_name, $values, $condition);
        }

        public function end_session($table_name, $values, $condition) {
            $user_data = $_SESSION[$this->key];

            if ($user_data["id"]) {
                return false;
            }
            
            if (time() - $user_data["last_activity"] == 1800) {
                $this->Database->update_table($table_name, $values, $condition);
                return true;
            }
        }

    }
?>