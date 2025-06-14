<?php 
  session_start();
  include "../global.php";
  include "../database.php";
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
    <a href="#" class="logout-btn">Logout</a>
    <h1>Welcome, <?php echo "{$_SESSION['user']['fname']} {$_SESSION["user"]['lname']}" ?></h1>

    <div class="info">
      <p><strong>Student ID:</strong> <?php echo $_SESSION['user']['id'] ?></p>
      <p><strong>Email:</strong> <?php echo $_SESSION['user']['email'] ?></p>
      <p><strong>Department:</strong> <?php echo $_SESSION['user']['dept_code'] ?></p>
    </div>

    <h2>Your Registered Courses</h2>
    <table>
      <thead>
        <tr><th>Course Code</th><th>Course Name</th></tr>
      </thead>
      <tbody>
        <?php 
            $Database = new Database(DB);
            $enroll_records = $Database->get_info("*", "ENROLL", "STU_NUM = ?", [$_SESSION["user"]["id"]], $all=true);
            $course_records = [];
            foreach ($enroll_records as $enroll_record) {
                $class_code = $enroll_record["CLASS_CODE"];
                $course_code = $Database->get_info("CRS_CODE", "CLASS", "CLASS_CODE = ?", [$class_code]);
                $course_record = $Database->get_info("*", "COURSE", "CRS_CODE = ?", [$course_code], $all=true);
                $course_records[] = $course_record[0];
            }

            
            foreach ($course_records as $record) {
                $course_code = $record["CRS_CODE"];
                $course_title = $record["CRS_TITLE"];
                echo "
                    <tr>
                        <td>{$course_code}</td>
                        <td>{$course_title}</td>
                    </tr>
                ";

            }
            
            //$stu_courses = $Database->get_info("COURSE_CODE", "CLASS", "CLASS_CODE = ?", [$class_code]);
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
