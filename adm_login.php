<?php 
    session_start();
    include "global.php";
    include "form_validator.php";
    include "database.php";
    include "session.php";
    include "config.php"; 

    define("KEYS", ["email", "password"]);

    $session = new Session("user", new Database(DB));
    unset($_SESSION["user"]);
    $session->user_logged_in("home.php");

    if ($_SERVER["REQUEST_METHOD"] !== "POST") return;

    $admin_email = $_POST['email'];
    $admin_password = $_POST['password'];

    define("TARGETS", [$admin_email, $admin_password]);

    $FormValidator = new FormValidator(REGEX, MAX_LENGTH, MIN_LENGTH, KEYS, TARGETS);
    $error = null;
    $form_status = null;

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

    if ($admin_email !== ADMIN_EMAIL) {
        $error = "Email not found.";
        return;
    }

    if (!password_verify($admin_password, ADMIN_PASSWORD_HASH)) {
        $error = "Incorrect password.";
        return;
    }

    $session->login_user([
        "role" => "admin",
        "email" => $admin_email,
        "last_activity" => time(),
        "name" => "System Administrator"
    ]);

    $form_status = "Login successful";
?>
