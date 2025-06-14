<?php  
  include "../adm_login.php"; 
  $POST = $_SERVER["REQUEST_METHOD"] == "POST";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Administrator Login</title>
    <link rel="stylesheet" href="../css/auth.css" />
  </head>
  <body>
    <?php echo $POST ? "<h2 style='color: green'>$form_status</h2>" : null; ?>
    <div class="auth-container">
      <h2>Administrator Login</h2>
      <form action="admin_login.php" method="POST">
        <input type="text" name="email" placeholder="Email" value="admin@university.ac.za" />
        <input type="password" name="password" placeholder="Password" value="admin" />
        <?php echo $POST ? "<p style='color: red'>$error</p>" : null; ?>
        <button type="submit">Login</button>
      </form>
    </div>
  </body>
</html>
