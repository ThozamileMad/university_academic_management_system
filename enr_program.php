<?php
  session_start();
  include "global.php";
  include "form_validator.php";
  include "database.php";
  include "session.php";

  $Database = new Database(DB);
  $session = new Session("user", $Database);
  $user = $_SESSION["user"];
  $session->user_not_logged_in("home.php");
  $session->end_session("STUDENT", ["LAST_ACTIVITY" => NULL], "STU_NUM = {$user['id']}", "home.php");

  $dept_code = $Database->get_info("DEPT_CODE", "STUDENT", "STU_NUM = ?", [$user["id"]]);
  if ($dept_code) {
    header("Location: student_dashboard.php");
    exit;
  }

  $POST = $_SERVER["REQUEST_METHOD"] == "POST"; 
  $error = "";
  if (!$POST) return;

  $program = $_POST["program"];
  if ($program == "") {
    $error = "<p style='color: red'>Please select a program</p>";
    return;
  }

  $prof_code = $Database->get_info("PROF_CODE", "PROFESSOR", "DEPT_CODE = ?", [$program]);
  $Database->update_table("STUDENT", ["DEPT_CODE" => $program, "PROF_CODE" => $prof_code], "STU_NUM = {$user['id']}");
  $_SESSION["user"]["dept_code"] = $program;

  header("Location: enroll_course.php");
  exit;
?>