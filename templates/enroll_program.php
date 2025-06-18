<?php 
  include "../enr_program.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Department Enrollment</title>
  <link rel="stylesheet" href="../css/enroll.css" />
</head>
<body>
  <div class="enroll-container">
    <h2>Department Enrollment</h2>
    <form action="enroll_program.php" method="POST">
      <label for="program">Select Department</label>
      <select name="program" id="program">
        <option value="">-- Choose a Department --</option>
        <?php 
            $departments = $Database->get_all_info("DEPARTMENT");
            foreach ($departments as $dept) {
                $code = $dept['DEPT_CODE'];
                $name = $dept['DEPT_NAME'];
                echo "<option value='$code'>DPT$code - $name</option>";
            }
        ?>
      </select>
      <?php echo $POST ? $error : null; ?>
      <button type="submit">Submit</button>
    </form>
  </div>
</body>
</html>
