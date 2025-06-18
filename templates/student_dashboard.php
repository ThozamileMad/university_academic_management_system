<?php 
  include "../stu_dashboard.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="../css/student_dashboard.css" />
</head>
<body>
  <div class="container">
    <div class="btn-div">
      <?php
        echo $dept_code ? "<a href='enroll_course.php' class='enroll-btn'>Enroll</a>" : "<a href='enroll_program.php' class='enroll-btn'>Enroll</a>"
      ?>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
    <h1>Welcome, <?php echo "{$user['fname']} {$user['lname']}" ?></h1>

    <div class="info">
      <p><strong>Student ID:</strong> <?php echo $user['id'] ?></p>
      <p><strong>Email:</strong> <?php echo $user['email'] ?></p>
      <p><strong>Department:</strong> 
        <?php 
          echo $dept_code ? $user['dept_code'] : "None";
        ?>
      </p>
    </div>

    <h2>Your Registered Courses</h2>
    <table>
      <thead>
        <tr><th>Course Code</th><th>Course Name</th></tr>
      </thead>
      <tbody>
        <?php 
            foreach ($rows as $row) {
              echo $row;
            }
        ?>
      </tbody>
    </table>
    <?php 
      echo count($rows) == 0 ? "<h2 style='text-align: center'>Student is not enrolled in any courses</h2>" : null;
    ?>
  </div>
</body>
</html>
