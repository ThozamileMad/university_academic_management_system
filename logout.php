<?php
  session_start();
  include "global.php";
  include "database.php";
  include "session.php";

  $Database = new Database(DB);
  $user = $_SESSION["user"];
  $session = new Session("user", $Database);
  switch ($user["role"]) {
    case "student":
        $session->logout_user(
            "STUDENT", 
            ["LAST_ACTIVITY" => NULL], 
            "STU_NUM = {$user['id']}", 
        );
        break;
    case "professor":
        $session->logout_user(
            "PROFESSOR", 
            ["LAST_ACTIVITY" => NULL], 
            "PROF_CODE = {$user['id']}", 
        );
        break;
    default:
        unset($_SESSION["user"]);
  }
?>