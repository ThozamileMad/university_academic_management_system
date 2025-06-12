<?php 
  $db_path = "sqlite.db";
  define("DB", new PDO("sqlite:$db_path"));

  define("REGEX", array(
    "name" => [
        // Disallow digits
        "/\d/",
        // Disallow special characters except apostrophes, hyphens, and spaces
        "/[^a-zA-Z\p{L}\s\'\-]/u",
        // Disallow leading/trailing spaces
        "/^\s+|\s+$/",
        // Disallow multiple spaces
        "/\s{2,}/",
        // Disallow multiple consecutive hyphens
        "/-{2,}/"
    ],
    "email" => [
        // Disallow leading/trailing whitespace
        "/^\s+|\s+$/",
        // Disallow any character not valid in an email
        "/[^a-zA-Z0-9@._\-]/",
        // Ensure it matches the basic email pattern (fail if it doesn't)
        "/^(?![^\@]+@[^\@]+\.[^\@]+$)/"
    ],
    "program" => [
        // Disallow empty string
        "/^$/",
        // Disallow anything that's not an uppercase letter or digit
        "/[^A-Z0-9]/"
    ],
    "password" => [
        // Disallow leading/trailing whitespace
        "/^\s+|\s+$/",
        // Disallow anything outside of letters, numbers, and common symbols
        "/[^a-zA-Z0-9!@#$%^&*()_\-+=]/"
    ]
  ));


  define("MAX_LENGTH", array(
    "name" => 50,
    "email" => 255,
    "program" => 6,
    "password" => 8,
  ));

  define("MIN_LENGTH", array(
    "name" => 1,
    "email" => 1,
    "program" => 1,
    "password" => 1,
  ));

  define("KEYS", [
    "name", 
    "name", 
    "email", 
    "password", 
    "password", 
    "program"
  ]);

  // Exit if logged in
  unset($_SESSION["stu_id"]);
  if (isset($_SESSION["stu_id"])) {
    header("Location: home.php");
    exit();
  }

  if ($_SERVER["REQUEST_METHOD"] != "POST") {
    return;
  }

  $dept_code = $_POST['program'];
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
  $Database = new Database(DB);
  $stu_num = strval((integer) $Database->get_last_primary_key("STU_NUM", "STUDENT") + 1);
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
    ["STU_NUM", "DEPT_CODE", "STU_LNAME", "STU_FNAME", "STU_EMAIL", "STU_PASSWORD", "LAST_ACTIVITY"], 
    [$stu_num, $dept_code, $stu_lname, $stu_fname, $stu_email, $hashed_password, time()],
  );

  // Login User
  $_SESSION["stu_id"] =  $stu_num;
  $_SESSION["dept_code"] = $dept_code;
  $_SESSION["stu_fname"] = $stu_fname;
  $_SESSION["stu_lname"] = $stu_lname;
  $_SESSION["stu_email"] = $stu_email;
  $_SESSION["last_activity"] = time();

  $form_status = "Form Submitted";
?>