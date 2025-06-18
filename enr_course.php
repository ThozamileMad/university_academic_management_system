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
  $current_time = date("Y-m-d H:i:s");

  $enroll_records = $Database->get_info("*", "ENROLL", "STU_NUM = ?", [$user["id"]], $all=true);
  $course_codes = [];
  foreach ($enroll_records as $record) {
    $course_codes[]  = $Database->get_info("CRS_CODE", "CLASS", "CLASS_CODE = ?", [$record["CLASS_CODE"]]);
  }

  $options = [];
  $course_records = $Database->get_all_info("COURSE");
  foreach ($course_records as $record) {
      $code = $record["CRS_CODE"];
      $title = $record["CRS_TITLE"];
      $dept_code = $record["DEPT_CODE"];
      if (!in_array($code, $course_codes) && $dept_code == $user["dept_code"]) {
        $options[] = "<option value='$code'>CS$code - {$title}</option>";
      }
  }

  $POST = $_SERVER["REQUEST_METHOD"] == "POST";
  if (!$POST) return;
  $course_code = $_POST["course"];
  $error = "";
  
  if ($course_code == "") {
    $error = "Please select a course.";
    return;
  }

  $class_code = $Database->get_info("CLASS_CODE", "CLASS", "CRS_CODE = ?", [$course_code]);
  $Database->insert_into_table(
    "ENROLL",
    ["CLASS_CODE", "STU_NUM", "ENROLL_DATE", "ENROLL_GRADE"], 
    [$class_code, $user["id"], $current_time, 0],
  );

  header("Location: student_dashboard.php");
  exit;
?>