<?php 
  session_start();
  include "global.php";
  include "form_validator.php";
  include "database.php";
  include "session.php";

  define("KEYS", [
    "name", 
    "name", 
    "email", 
    "password", 
    "password", 
    "program"
  ]);

  // Exit if logged in
  $Database = new Database(DB);
  $session = new Session("user", $Database);
  $session->user_logged_in("home.php");
  $POST = $_SERVER["REQUEST_METHOD"] == "POST";
  if (!$POST) {
    return;
  }
  
  $stu_lname = $_POST['lname'];
  $stu_fname = $_POST['fname'];
  $stu_email = $_POST['email'];
  $stu_password = $_POST['password'];
  $password_confirmation = $_POST["confirm_password"];
  
  define("TARGETS", [
    $stu_fname, 
    $stu_lname, 
    $stu_email, 
    $stu_password, 
    $password_confirmation, 
    $dept_code
  ]);

  $FormValidator = new FormValidator(REGEX, MAX_LENGTH, MIN_LENGTH, KEYS, TARGETS);
  $stu_num = strval((integer) $Database->get_last_primary_key("STU_NUM", "STUDENT") + 1);
  $error = null;

  // Regex Validation Process
  $valid_regex = $FormValidator->validate_all_regex();

  if (is_string($valid_regex)) {
    $error = "Invalid characters: $valid_regex.";
    return;
  }

  // Max Length Validation Process
  $valid_max_length = $FormValidator->validate_all_length("max");

  if (is_string($valid_max_length)) {
    $error = "Too many characters: $valid_max_length.";
    return;
  }

  // Min Length Validation Process
  $valid_min_length = $FormValidator->validate_all_length("min");

  if (is_string($valid_min_length)) {
    $error = "Too few characters: $valid_min_length.";
    return;
  }

  // Check For Existing User
  if ($Database->check_user_existence("STU_EMAIL", "STUDENT", $stu_email)) {
    $error = "Email already in use.";
    return;
  }

  // Check Confirm Password Match
  if ($stu_password != $password_confirmation) {
    $error = "Passwords don't match.";
    return;
  }

  // Add data to database
  $hashed_password = password_hash($stu_password, PASSWORD_DEFAULT);
  $Database->insert_into_table(
    "STUDENT",
    ["STU_NUM", "STU_LNAME", "STU_FNAME", "STU_EMAIL", "STU_PASSWORD", "LAST_ACTIVITY"], 
    [$stu_num, $stu_lname, $stu_fname, $stu_email, $hashed_password, time()],
  );

  // Login User
  $session->login_user([
    "role" => "student",
    "id" => $stu_num,
    "fname" => $stu_fname,
    "lname" => $stu_lname,
    "email" => $stu_email,
    "last_activity" => time()
  ]);

  header("Location: student_dashboard.php");
?>