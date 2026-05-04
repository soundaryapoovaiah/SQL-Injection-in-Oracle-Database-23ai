<!--
SEED Lab: SQL Injection Education Web plateform
Author: Kailiang Ying
Email: kying@syr.edu
-->

<!--
SEED Lab: SQL Injection Education Web plateform
Enhancement Version 1
Date: 12th April 2018
Developer: Kuber Kohli
-->

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="css/style_home.css" type="text/css" rel="stylesheet">

  <!-- Browser Tab title -->
  <title>SQLi Lab</title>
</head>
<body>
  <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #3EA055;">
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="unsafe_home.php" ><img src="seed_logo.png" style="height: 40px; width: 200px;" alt="SEEDLabs"></a>

      <?php
      // Start session once (avoid calling session_start multiple times)
      if (session_status() === PHP_SESSION_NONE) {
        session_start();
      }

      // -----------------------------
      // Helper: show student-friendly error page and exit
      // -----------------------------
      function showStudentErrorAndExit($msg, $conn = null, $stid = null) {
        // Close resources if provided
        if ($stid) { @oci_free_statement($stid); }
        if ($conn) { @oci_close($conn); }

        // Close navbar HTML that started before PHP
        echo "</div>";
        echo "</nav>";

        // Student-friendly message
        echo "<div class='container text-center' style='margin-top:80px;'>";
        echo "<div class='alert alert-warning'>";
        echo htmlspecialchars($msg, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
        echo "</div>";
        echo "<a class='btn btn-secondary' href='index.html'>Go back</a>";
        echo "</div>";

        exit;
      }

      // -----------------------------
      // Read inputs safely (avoid Undefined index notices)
      // -----------------------------
      $input_uname = $_GET['username'] ?? "";
      $input_pwd   = $_GET['Password'] ?? "";
      $hashed_pwd  = sha1($input_pwd);

      // If the session is already established, reuse it
      if ($input_uname==="" && $hashed_pwd===sha1("") && !empty($_SESSION['name']) && !empty($_SESSION['pwd'])) {
        $input_uname = $_SESSION['name'];
        $hashed_pwd  = $_SESSION['pwd'];
        // NOTE: $input_pwd remains "", so we will NOT display plaintext in this session-reuse case.
      }

      // -----------------------------
      // Function to create a DB connection (Oracle)
      // -----------------------------
      function getDB() {
        $dbhost  = "oracle";
        $dbuser  = "seed";
        $dbpass  = "dees";
        $service = "FREEPDB1";

        $connStr = "//{$dbhost}:1521/{$service}";
        $conn = oci_connect($dbuser, $dbpass, $connStr, "AL32UTF8");

        if (!$conn) {
          $e = oci_error();
          // Log real error for instructor/TA
          error_log("Oracle connection failed: " . ($e['message'] ?? 'unknown'));
          return null;
        }
        return $conn;
      }

      // -----------------------------
      // LOGIN QUERY (Oracle) - intentionally vulnerable for SQLi lab
      // -----------------------------
      $conn = getDB();
      if (!$conn) {
        showStudentErrorAndExit(
          "Database connection failed. Please wait a minute and try again. If it persists, restart the lab.",
          null,
          null
        );
      }

      // EXECUTED SQL must use the hash (real authentication)
      $sql_exec = "SELECT id, name, eid, salary, birth, ssn, phoneNumber, address, email, nickname, password
                   FROM credential
                   WHERE name='$input_uname' AND password='$hashed_pwd'";

      $stid = oci_parse($conn, $sql_exec);
      if (!$stid) {
        $e = oci_error($conn);

        // Log full details for instructor/TA
        error_log("Oracle parse error: " . ($e['message'] ?? 'unknown'));
        error_log("Offending SQL: " . $sql_exec);

        // Student-friendly message (no ORA codes shown)
        showStudentErrorAndExit(
          "Your input caused a database syntax error. If you're using special characters (quotes/spaces), URL-encode them when using curl.",
          $conn,
          null
        );
      }

      $r = oci_execute($stid);
      if (!$r) {
        $e = oci_error($stid);

        error_log("Oracle execute error: " . ($e['message'] ?? 'unknown'));
        error_log("Offending SQL: " . $sql_exec);

        showStudentErrorAndExit(
          "Database rejected the request. Please check your input encoding and try again.",
          $conn,
          $stid
        );
      }

      $return_arr = array();
      while (($row = oci_fetch_assoc($stid)) != false) {
        // OCI returns uppercase keys; convert to lowercase to match existing code expectations
        $row_lower = array();
        foreach ($row as $k => $v) {
          $row_lower[strtolower($k)] = $v;
        }
        $return_arr[] = $row_lower;
      }
      oci_free_statement($stid);

      /* convert the array type to json format and read out*/
      $json_str = json_encode($return_arr);
      $json_a = json_decode($json_str,true);

      $id = isset($json_a[0]['id']) ? $json_a[0]['id'] : "";
      $name = isset($json_a[0]['name']) ? $json_a[0]['name'] : "";
      $eid = isset($json_a[0]['eid']) ? $json_a[0]['eid'] : "";
      $salary = isset($json_a[0]['salary']) ? $json_a[0]['salary'] : "";
      $birth = isset($json_a[0]['birth']) ? $json_a[0]['birth'] : "";
      $ssn = isset($json_a[0]['ssn']) ? $json_a[0]['ssn'] : "";
      $phoneNumber = isset($json_a[0]['phonenumber']) ? $json_a[0]['phonenumber'] : "";
      $address = isset($json_a[0]['address']) ? $json_a[0]['address'] : "";
      $email = isset($json_a[0]['email']) ? $json_a[0]['email'] : "";
      $pwd = isset($json_a[0]['password']) ? $json_a[0]['password'] : "";
      $nickname = isset($json_a[0]['nickname']) ? $json_a[0]['nickname'] : "";

      // DISPLAYED SQL: show plaintext only when login succeeds AND password was provided
      // Otherwise show the hash (like MySQL lab behavior you described).
    // Show plaintext ONLY if the entered password matches the stored hash
    
$passwordMatched = ($pwd !== "" && hash_equals($pwd, $hashed_pwd));

$shown_pwd = ($passwordMatched && $input_pwd !== "") ? $input_pwd : $hashed_pwd;

      $sql_display = "SELECT id, name, eid, salary, birth, ssn, phoneNumber, address, email, nickname, password
FROM credential
WHERE name='$input_uname' AND password='$shown_pwd'";

      echo htmlspecialchars($sql_display, ENT_NOQUOTES | ENT_SUBSTITUTE, "UTF-8");
      echo "\n</pre>\n";

      if ($id !== "") {
        // If id exists that means user exists and is successfully authenticated
        drawLayout($id,$name,$eid,$salary,$birth,$ssn,$pwd,$nickname,$email,$address,$phoneNumber);
      } else {
        // User authentication failed (normal case)
        echo "</div>";
        echo "</nav>";
        echo "<div class='container text-center' style='margin-top:80px;'>";
        echo "<div class='alert alert-danger'>";
        echo "The account information you provided does not exist.";
        echo "<br>";
        echo "</div>";
        echo "<a class='btn btn-secondary' href='index.html'>Go back</a>";
        echo "</div>";
        oci_close($conn);
        return;
      }

      // close the sql connection
      oci_close($conn);

      function drawLayout($id,$name,$eid,$salary,$birth,$ssn,$pwd,$nickname,$email,$address,$phoneNumber){
        if ($id !== "") {
          // session already started at top; just assign values
          $_SESSION['id'] = $id;
          $_SESSION['eid'] = $eid;
          $_SESSION['name'] = $name;
          $_SESSION['pwd'] = $pwd;
        } else {
          echo "can not assign session";
        }

        if ($name != "Admin") {
          // If the user is a normal user.
          echo "<ul class='navbar-nav mr-auto mt-2 mt-lg-0' style='padding-left: 30px;'>";
          echo "<li class='nav-item active'>";
          echo "<a class='nav-link' href='unsafe_home.php'>Home <span class='sr-only'>(current)</span></a>";
          echo "</li>";
          echo "<li class='nav-item'>";
          echo "<a class='nav-link' href='unsafe_edit_frontend.php'>Edit Profile</a>";
          echo "</li>";
          echo "</ul>";
          echo "<button onclick='logout()' type='button' id='logoffBtn' class='nav-link my-2 my-lg-0'>Logout</button>";
          echo "</div>";
          echo "</nav>";
          echo "<div class='container col-lg-4 col-lg-offset-4 text-center'>";
          echo "<br><h1><b> $name Profile </b></h1>";
          echo "<hr><br>";
          echo "<table class='table table-striped table-bordered'>";
          echo "<thead class='thead-dark'>";
          echo "<tr>";
          echo "<th scope='col'>Key</th>";
          echo "<th scope='col'>Value</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tr>";
          echo "<th scope='row'>Employee ID</th>";
          echo "<td>$eid</td>";
          echo "</tr>";
          echo "<tr>";
          echo "<th scope='row'>Salary</th>";
          echo "<td>$salary</td>";
          echo "</tr>";
          echo "<tr>";
          echo "<th scope='row'>Birth</th>";
          echo "<td>$birth</td>";
          echo "</tr>";
          echo "<tr>";
          echo "<th scope='row'>SSN</th>";
          echo "<td>$ssn</td>";
          echo "</tr>";
          echo "<tr>";
          echo "<th scope='row'>NickName</th>";
          echo "<td>$nickname</td>";
          echo "</tr>";
          echo "<tr>";
          echo "<th scope='row'>Email</th>";
          echo "<td>$email</td>";
          echo "</tr>";
          echo "<tr>";
          echo "<th scope='row'>Address</th>";
          echo "<td>$address</td>";
          echo "</tr>";
          echo "<tr>";
          echo "<th scope='row'>Phone Number</th>";
          echo "<td>$phoneNumber</td>";
          echo "</tr>";
          echo "</table>";
        } else {
          // if user is admin.
          $conn = getDB();
          if (!$conn) {
            // minimal admin-safe message
            echo "</div></nav><div class='container text-center' style='margin-top:80px;'><div class='alert alert-warning'>Database connection failed.</div></div>";
            return;
          }

          $sql = "SELECT id, name, eid, salary, birth, ssn, password, nickname, email, address, phoneNumber
                  FROM credential";

          $stid = oci_parse($conn, $sql);
          if (!$stid) {
            $e = oci_error($conn);
            error_log("Oracle parse error (admin): " . ($e['message'] ?? 'unknown'));
            echo "</div></nav><div class='container text-center' style='margin-top:80px;'><div class='alert alert-warning'>Database error.</div></div>";
            oci_close($conn);
            return;
          }

          $r = oci_execute($stid);
          if (!$r) {
            $e = oci_error($stid);
            error_log("Oracle execute error (admin): " . ($e['message'] ?? 'unknown'));
            echo "</div></nav><div class='container text-center' style='margin-top:80px;'><div class='alert alert-warning'>Database error.</div></div>";
            oci_free_statement($stid);
            oci_close($conn);
            return;
          }

          $return_arr = array();
          while (($row = oci_fetch_assoc($stid)) != false) {
            $row2 = array();
            foreach ($row as $k => $v) {
              $row2[strtolower($k)] = $v;
            }
            array_push($return_arr, $row2);
          }
          oci_free_statement($stid);

          $json_str = json_encode($return_arr);
          $json_aa = json_decode($json_str,true);
          oci_close($conn);
          $max = sizeof($json_aa);

          echo "<ul class='navbar-nav mr-auto mt-2 mt-lg-0' style='padding-left: 30px;'>";
          echo "<li class='nav-item active'>";
          echo "<a class='nav-link' href='unsafe_home.php'>Home <span class='sr-only'>(current)</span></a>";
          echo "</li>";
          echo "<li class='nav-item'>";
          echo "<a class='nav-link' href='unsafe_edit_frontend.php'>Edit Profile</a>";
          echo "</li>";
          echo "</ul>";
          echo "<button onclick='logout()' type='button' id='logoffBtn' class='nav-link my-2 my-lg-0'>Logout</button>";
          echo "</div>";
          echo "</nav>";
          echo "<div class='container'>";
          echo "<br><h1 class='text-center'><b> User Details </b></h1>";
          echo "<hr><br>";
          echo "<table class='table table-striped table-bordered'>";
          echo "<thead class='thead-dark'>";
          echo "<tr>";
          echo "<th scope='col'>Username</th>";
          echo "<th scope='col'>EId</th>";
          echo "<th scope='col'>Salary</th>";
          echo "<th scope='col'>Birthday</th>";
          echo "<th scope='col'>SSN</th>";
          echo "<th scope='col'>Nickname</th>";
          echo "<th scope='col'>Email</th>";
          echo "<th scope='col'>Address</th>";
          echo "<th scope='col'>Ph. Number</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          for ($i=0; $i < $max; $i++) {
            $i_id = $json_aa[$i]['id'];
            $i_name= $json_aa[$i]['name'];
            $i_eid= $json_aa[$i]['eid'];
            $i_salary= $json_aa[$i]['salary'];
            $i_birth= $json_aa[$i]['birth'];
            $i_ssn= $json_aa[$i]['ssn'];
            $i_pwd = $json_aa[$i]['password'];
            $i_nickname= $json_aa[$i]['nickname'];
            $i_email= $json_aa[$i]['email'];
            $i_address= $json_aa[$i]['address'];
            $i_phoneNumber= $json_aa[$i]['phonenumber'];

            echo "<tr>";
            echo "<th scope='row'> $i_name</th>";
            echo "<td>$i_eid</td>";
            echo "<td>$i_salary</td>";
            echo "<td>$i_birth</td>";
            echo "<td>$i_ssn</td>";
            echo "<td>$i_nickname</td>";
            echo "<td>$i_email</td>";
            echo "<td>$i_address</td>";
            echo "<td>$i_phoneNumber</td>";
            echo "</tr>";
          }

          echo "</tbody>";
          echo "</table>";
        }
      }
      ?>
      <br><br>
      <div class="text-center">
        <p>
          Copyright &copy; SEED LABs
        </p>
      </div>
    </div>
    <script type="text/javascript">
    function logout(){
      location.href = "logoff.php";
    }
    </script>
  </body>
</html>
