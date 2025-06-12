<?php 
  include "../stu_login.php"; 
  $POST = $_SERVER["REQUEST_METHOD"] == "POST";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Login</title>
    <link rel="stylesheet" href="../css/auth.css" />
  </head>
  <body>
    <?php echo $POST ? "<h2 style='color: green'>$form_status</h2>" : null; ?>
    <div class="auth-container">
      <h2>Student Login</h2>
      <form action="student_login.php" method="POST">
        <input type="text" name="email" placeholder="Email" value="default@default" />
        <input type="password" name="password" placeholder="Password" value="default" />
        <?php echo $POST ? "<p style='color: red'>$error</p>" : null; ?>
        <button type="submit">Login</button>
      </form>
      <div class="link-text">
        Don’t have an account? <a href="student_signup.php">Sign Up</a>
      </div>
    </div>
  </body>
</html>
