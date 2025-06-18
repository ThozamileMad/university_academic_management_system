<?php 
  session_start();
  include "global.php";
  include "form_validator.php";
  include "database.php";
  include "session.php";

  define("KEYS", [
    "email", 
    "password", 
  ]);

  // Exit if logged in
  $Database = new Database(DB);
  $session = new Session("user", $Database);
  $session->user_logged_in("home.php");

  $POST = $_SERVER["REQUEST_METHOD"] == "POST";
  if (!$POST) {
    return;
  }

  $stu_email = $_POST['email'];
  $stu_password = $_POST['password'];
  
  define("TARGETS", [
    $stu_email, 
    $stu_password, 
  ]);

  $FormValidator = new FormValidator(REGEX, MAX_LENGTH, MIN_LENGTH, KEYS, TARGETS);
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
  if (!$Database->check_user_existence("STU_EMAIL", "STUDENT", $stu_email)) {
    $error = "Email not found.";
    return;
  }
  
  // Fetch hashed password from DB using email
  $hashed_password = $Database->get_info("STU_PASSWORD", "STUDENT", "STU_EMAIL = ?", [$stu_email]);
  
  if (!password_verify($stu_password, $hashed_password)) {
      $error = "Incorrect password.";
      return;
  }

  $id = $Database->get_info("STU_NUM", "STUDENT", "STU_EMAIL = ?", [$stu_email]);
  $Database->update_table("STUDENT", ["LAST_ACTIVITY" => time()], "STU_NUM = $id");
  
  // 3. Login user
  $session->login_user([
      "role" => "student",
      "id" => $id,
      "fname" => $Database->get_info("STU_FNAME", "STUDENT", "STU_EMAIL = ?", [$stu_email]),
      "lname" => $Database->get_info("STU_LNAME", "STUDENT", "STU_EMAIL = ?", [$stu_email]),
      "email" => $stu_email,
      "last_activity" => time()
  ]);
  
  header("Location: student_dashboard.php");
?>