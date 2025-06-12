  <?php 
    session_start();
    
    function get_primary_keys($column_name, $table_name) {
      $result = DB->query("SELECT $column_name FROM $table_name");
      return $result;
    }

    function validate($regex_arr, $target_str) {
      foreach ($regex_arr as $regex) {
        if (preg_match($regex, $target_str, $matches)) {
          return false;
        }
      }

      return true;
    }

    function validate_all_inputs() {
      foreach (REGEX as $key => $regex_arr) {
        if (validate($regex_arr, $_POST[$key])) {
          return false;
        }
      }

      return true;
    }

    if (!($_SERVER["REQUEST_METHOD"] == "POST")) {
      return;
    }

    define("REGEX", array(
      "name" => [
        '/\d/',
        '/[!@#$%^&*(),.?":{}|<>]/',
        '/^\s+|\s+$/',
        '/\s{2,}/',
        '/[-]{2,}/',
        '/[^\p{L}\s\'-]/u',
      ],
      "email" => [
        '/^\s+|\s+$/',
        '/[^a-zA-Z0-9@\.\-_]/',
        '/^[^@]+@[^@]+\.[^@]+$/', 
      ],
      "program" => [
        '/^$/',
        '/[^A-Z0-9]/'
      ],
      "password" => [
        '/^.{0,7}$/',
        '/^\s+|\s+$/',
        '/[^a-zA-Z0-9!@#$%^&*()_\-+=]/'
      ],
    ));


    $db_path = "sqlite.db";
    define("DB", new PDO("sqlite:$db_path"));
    
    if (!validate_all_inputs()) {
      echo "<h2 style='color: red'>Invalid Input</h2>";
      return;
    }

    $stmt = DB->prepare("
      INSERT INTO STUDENT 
        (STU_NUM, DEPT_CODE, STU_LNAME, STU_FNAME, STU_EMAIL, STU_PASSWORD) 
        VALUES (:STU_NUM, :DEPT_CODE, :STU_LNAME, :STU_FNAME, :STU_EMAIL, :STU_PASSWORD)
      ");

    $STU_NUM = strval((integer) get_primary_keys("STU_NUM", "STUDENT") + 1);
    $stmt->bindValue(':STU_NUM', $new_id);
    $stmt->bindValue(':DEPT_CODE', $_POST['program']);
    $stmt->bindValue(':STU_LNAME', $_POST['lname']);
    $stmt->bindValue(':STU_FNAME', $_POST['fname']);
    $stmt->bindValue(':STU_EMAIL', $_POST['email']);
    $stmt->bindValue(':STU_PASSWORD', password_hash($_POST['password'], PASSWORD_DEFAULT));
    $stmt->execute();

  ?>