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
  $session = new Session("user", new Database(DB));
  $session->user_logged_in("home.php");

  if ($_SERVER["REQUEST_METHOD"] != "POST") {
    return;
  }

  $prof_email = $_POST['email'];
  $prof_password = $_POST['password'];
  
  define("TARGETS", [
    $prof_email, 
    $prof_password, 
  ]);

  $FormValidator = new FormValidator(REGEX, MAX_LENGTH, MIN_LENGTH, KEYS, TARGETS);
  $Database = new Database(DB);
  $error = null;
  $form_status = null;

/* Test Passwords
  $passwords = [
    "Hudson123!",
    "Zinhle456!",
    "Brian789!",
    "Natalie321!",
    "Thando654!",
    "Jason987!",
    "Sibongile147!",
    "Mandla258!",
    "Karen369!",
    "Dumisani741!"
];
*/

  // Validation Steps
  if (is_string($FormValidator->validate_all_regex())) {
    $error = "Invalid characters.";
    return;
  }

  if (is_string($FormValidator->validate_all_length("max"))) {
    $error = "Too many characters.";
    return;
  }

  if (is_string($FormValidator->validate_all_length("min"))) {
    $error = "Too few characters.";
    return;
  }

  // Check existence
  if (!$Database->check_user_existence("PROF_EMAIL", "PROFESSOR", $prof_email)) {
    $error = "Email not found.";
    return;
  }

  // Get hashed password
  $hashed_password = $Database->get_info("PROF_PASSWORD", "PROFESSOR", "PROF_EMAIL = ?", [$prof_email]);
  if (!password_verify($prof_password, $hashed_password)) {
    $error = "Incorrect password.";
    return;
  }

  // Login user
  $session->login_user([
    "role" => "professor",
    "id" => $Database->get_info("PROF_CODE", "PROFESSOR", "PROF_EMAIL = ?", [$prof_email]),
    "dept_code" => $Database->get_info("DEPT_CODE", "PROFESSOR", "PROF_EMAIL = ?", [$prof_email]),
    "fname" => $Database->get_info("PROF_FNAME", "PROFESSOR", "PROF_EMAIL = ?", [$prof_email]),
    "lname" => $Database->get_info("PROF_LNAME", "PROFESSOR", "PROF_EMAIL = ?", [$prof_email]),
    "initial" => $Database->get_info("PROF_INITIAL", "PROFESSOR", "PROF_EMAIL = ?", [$prof_email]),
    "email" => $prof_email,
    "speciality" => $Database->get_info("PROF_SPECIALITY", "PROFESSOR", "PROF_EMAIL = ?", [$prof_email]),
    "rank" => $Database->get_info("PROF_RANK", "PROFESSOR", "PROF_EMAIL = ?", [$prof_email]),
    "last_activity" => time()
  ]);

  $form_status = "Login successful";
?>
