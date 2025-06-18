<?php 
  include "../enr_course.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Course Enrollment</title>
  <link rel="stylesheet" href="../css/enroll.css" />
</head>
<body>
  <div class="enroll-container">
    <h2>Course Enrollment</h2>
    <form action="enroll_course.php" method="POST">
      <label for="course">Select Course</label>
      <select name="course" id="course">
        <option value="">-- Choose a course --</option>
        <?php 
          foreach ($options as $option) {
            echo $option;
          }
        ?>
      </select>
      <?php echo $POST ? "<p style='color: red'>$error</p>" : null; ?>
      <button type="submit">Enroll</button>
    </form>
    
  </div>
</body>
</html>
