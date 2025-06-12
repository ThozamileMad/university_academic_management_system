<?php 
  session_start();
  include "form_validator.php";
  include "../database.phpdatabase.php";
  include "signup.php";

  $POST = $_SERVER["REQUEST_METHOD"] == "POST";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Sign Up</title>
    <link rel="stylesheet" href="./css/auth.css" />
  </head>
  <body>
    <?php echo $POST ? "<h2 style='color: green'>$form_status</h2>" : null; ?>
    <div class="auth-container">
      <h2>Student Sign Up</h2>
      <form action="student_signup.php" method="POST">
        <input type="text" name="fname" placeholder="First Name" value="default"  />
        <input type="text" name="lname" placeholder="Last Name" value="default" />
        <input type="text" name="email" placeholder="Email" value="default@default" />
        <select name="program" required>
          <option value="">Select Program</option>
          <option value="CS101">BSc Computer Science</option>
          <option value="ENG201">BEng Electrical Engineering</option>
          <option value="BUS301">BBA Business Administration</option>
        </select>
        <input type="password" name="password" placeholder="Password" value="default" />
        <input type="password" name="confirm_password" placeholder="Confirm Password" value="default" />
        <?php echo $POST ? "<p style='color: red'>$error</p>" : null; ?>
        <button type="submit">Sign Up</button>
      </form>
      <div class="link-text">
        Already have an account? <a href="student_login.php">Login</a>
      </div>
    </div>
  </body>
</html>
