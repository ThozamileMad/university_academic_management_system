<?php 
  include "../stu_signup.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Sign Up</title>
    <link rel="stylesheet" href="../css/auth.css" />
  </head>
  <body>
    <div class="auth-container">
      <h2>Student Sign Up</h2>
      <form action="student_signup.php" method="POST">
        <input type="text" name="fname" placeholder="First Name" value="default"  />
        <input type="text" name="lname" placeholder="Last Name" value="default" />
        <input type="text" name="email" placeholder="Email" value="default@default" />
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
