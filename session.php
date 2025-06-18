<?php 
    class Session {
        public $key;
        public $Database;
        public $session_lifetime;

        public function __construct($key, $Database) {
            $this->key = $key;
            $this->Database = $Database;
            $this->session_lifetime = 1800;
        }

        public function login_user($session_data) {
            $_SESSION[$this->key] = $session_data;
        }

        public function user_logged_in($redirect_path) {
            isset($_SESSION[$this->key]) ? header("Location: $redirect_path") : null;
        }

        public function user_not_logged_in($redirect_path) {
            !isset($_SESSION[$this->key]) ? header("Location: $redirect_path") : null;
        }

        public function logout_user($table_name, $values, $condition, $redirect_path="./templates/home.php") {
            unset($_SESSION[$this->key]);
            $this->Database->update_table($table_name, $values, $condition);
            header("Location: $redirect_path");
        }

        public function end_session($table_name, $values, $condition, $redirect_path="./templates/home.php") {
            $user_data = $_SESSION[$this->key];
            if (time() - $user_data["last_activity"] >= $this->session_lifetime) {
                $this->logout_user($table_name, $values, $condition, $redirect_path);
                return true;
            }
        }

    }
?>