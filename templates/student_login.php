<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Student Login - UAMS</title>
    <link rel="stylesheet" href="./css/auth.css" />
  </head>
  <body>
    <div class="auth-container">
      <h2>Student Login</h2>
      <form action="login-student.php" method="POST">
        <input type="text" name="stu_num" placeholder="Student Number" required />
        <input type="password" name="stu_password" placeholder="Password" required />
        <button type="submit">Login</button>
      </form>
      <div class="link-text">
        Don't have an account? <a href="student_signup.php">Sign Up</a>
      </div>
    </div>
  </body>
</html>
