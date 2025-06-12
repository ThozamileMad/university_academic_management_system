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
  $session = new Session("student", new Database(DB));
  $session->user_logged_in("home.php");

  if ($_SERVER["REQUEST_METHOD"] != "POST") {
    return;
  }

  $stu_email = $_POST['email'];
  $stu_password = $_POST['password'];
  
  define("TARGETS", [
    $stu_email, 
    $stu_password, 
  ]);

  $FormValidator = new FormValidator(REGEX, MAX_LENGTH, MIN_LENGTH, KEYS, TARGETS);
  $Database = new Database(DB);
  $error = null;
  $form_status = null;

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

  // Get database credentials 
  function get_info($column_name, $table, $condition, $params) {
    $pdo = DB;
    $sql = "SELECT $column_name FROM $table WHERE $condition";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
  }
  
  // Fetch hashed password from DB using email
  $hashed_password = get_info("STU_PASSWORD", "STUDENT", "STU_EMAIL = ?", [$stu_email]);
  
  if (!password_verify($stu_password, $hashed_password)) {
      $error = "Incorrect password.";
      return;
  }

  
  // 3. Login user
  $session->login_user([
      "id" => get_info("STU_NUM", "STUDENT", "STU_EMAIL = ?", [$stu_email]),
      "dept_code" => get_info("DEPT_CODE", "STUDENT", "STU_EMAIL = ?", [$stu_email]),
      "fname" => get_info("STU_FNAME", "STUDENT", "STU_EMAIL = ?", [$stu_email]),
      "lname" => get_info("STU_LNAME", "STUDENT", "STU_EMAIL = ?", [$stu_email]),
      "email" => $stu_email,
      "last_activity" => time()
  ]);
  
  $form_status = "Form Submitted";
?>