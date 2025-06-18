<?php
  session_start();
  include "../global.php";
  include "../database.php";
  include "../session.php";
  
  $Database = new Database(DB);
  $user = $_SESSION["user"];
  $dept_code = array_search("dept_code", array_keys($user));

  $session = new Session("user", $Database);
  $session->user_not_logged_in("home.php");
  $session->end_session("STUDENT", ["LAST_ACTIVITY" => NULL], "STU_NUM = {$user['id']}", "home.php");

  $enroll_records = $Database->get_info("*", "ENROLL", "STU_NUM = ?", [$user["id"]], $all=true);
  $course_records = [];
  foreach ($enroll_records as $enroll_record) {
      $class_code = $enroll_record["CLASS_CODE"];
      $course_code = $Database->get_info("CRS_CODE", "CLASS", "CLASS_CODE = ?", [$class_code]);
      $course_record = $Database->get_info("*", "COURSE", "CRS_CODE = ?", [$course_code], $all=true);
      $course_records[] = $course_record[0];
  }

  $rows = [];
  foreach ($course_records as $record) {
      $course_code = $record["CRS_CODE"];
      $course_title = $record["CRS_TITLE"];
      $rows[] = "
          <tr>
              <td>{$course_code}</td>
              <td>{$course_title}</td>
          </tr>
      ";
  }
?>