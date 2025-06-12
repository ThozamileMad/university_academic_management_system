<?php 
  $db_path = "../sqlite.db";
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
?>