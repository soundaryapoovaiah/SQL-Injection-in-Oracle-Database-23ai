<!--
SEED Lab: SQL Injection Education Web plateform
Author: Kailiang Ying
Email: kying@syr.edu
-->

<!--
SEED Lab: SQL Injection Education Web plateform
Enhancement Version 1.
Date: 13th April 2018
Developer: Kuber Kohli

Update: Implemented Form class from bootstrap to get a nice UI for edit profile form.
The php scripts populates the fields with existing values. The logout button triggers
a javascript function to redirect to login page.
-->

<?php
session_start();
if (!isset($_SESSION['name'])) {
  header("Location: index.php");
  exit();
}
$uname = $_SESSION['name'];

/**
 * Create an Oracle DB connection (OCI8).
 * Update $dbhost / $service if your Oracle setup differs.
 */
function getDB() {
  $dbhost  = "oracle";     // docker-compose service name (recommended)
  $dbport  = "1521";
  $service = "freepdb1";   // PDB service name
  $dbuser  = "seed";
  $dbpass  = "dees";

  $tns = "(DESCRIPTION=
            (ADDRESS=(PROTOCOL=TCP)(HOST=$dbhost)(PORT=$dbport))
            (CONNECT_DATA=(SERVICE_NAME=$service))
          )";

  $conn = oci_connect($dbuser, $dbpass, $tns, "AL32UTF8");
  if (!$conn) {
    $e = oci_error();
    die("Connection failed: " . htmlentities($e['message']));
  }
  return $conn;
}

// Fetch current user's row
$conn = getDB();

$sql = "SELECT id, name, eid, salary, birth, ssn, phoneNumber, address, email, nickname, password
        FROM credential
        WHERE name = :uname";

$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ":uname", $uname);
oci_execute($stid);

$row = oci_fetch_assoc($stid);
if (!$row) {
  // If user not found, avoid undefined-index warnings and show a clean message.
  die("User profile not found for: " . htmlentities($uname));
}

// OCI returns keys as UPPERCASE by default
$name        = $row['NAME'] ?? '';
$eid         = $row['EID'] ?? '';
$phoneNumber = $row['PHONENUMBER'] ?? '';
$address     = $row['ADDRESS'] ?? '';
$email       = $row['EMAIL'] ?? '';
$pwd         = $row['PASSWORD'] ?? '';
$nickname    = $row['NICKNAME'] ?? '';

oci_free_statement($stid);
oci_close($conn);
?>

<html>
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
      <a class="navbar-brand" href="unsafe_home.php" >
        <img src="seed_logo.png" style="height: 40px; width: 200px;" alt="SEEDLabs">
      </a>
      <ul class='navbar-nav mr-auto mt-2 mt-lg-0' style='padding-left: 30px;'>
        <li class='nav-item'>
          <a class='nav-link' href='unsafe_home.php'>Home</a>
        </li>
        <li class='nav-item active'>
          <a class='nav-link' href='unsafe_edit_frontend.php'>Edit Profile</a>
        </li>
      </ul>
      <button onclick='logout()' type='button' id='logoffBtn' class='nav-link my-2 my-lg-0'>Logout</button>
    </div>
  </nav>

  <div class="container col-lg-4 col-lg-offset-4 text-center" style="padding-top: 50px; text-align: center;">
    <?php
      // session already started at top
      $sess_name = $_SESSION["name"];
      echo "<h2><b>" . htmlentities($sess_name) . "'s Profile Edit</b></h2><hr><br>";
    ?>

    <form action="unsafe_edit_backend.php" method="get">
      <div class="form-group row">
        <label for="NickName" class="col-sm-4 col-form-label">NickName</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="NickName" name="NickName"
                 placeholder="NickName"
                 value="<?php echo htmlentities($nickname); ?>">
        </div>
      </div>

      <div class="form-group row">
        <label for="Email" class="col-sm-4 col-form-label">Email</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="Email" name="Email"
                 placeholder="Email"
                 value="<?php echo htmlentities($email); ?>">
        </div>
      </div>

      <div class="form-group row">
        <label for="Address" class="col-sm-4 col-form-label">Address</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="Address" name="Address"
                 placeholder="Address"
                 value="<?php echo htmlentities($address); ?>">
        </div>
      </div>

      <div class="form-group row">
        <label for="PhoneNumber" class="col-sm-4 col-form-label">Phone Number</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="PhoneNumber" name="PhoneNumber"
                 placeholder="PhoneNumber"
                 value="<?php echo htmlentities($phoneNumber); ?>">
        </div>
      </div>

      <div class="form-group row">
        <label for="Password" class="col-sm-4 col-form-label">Password</label>
        <div class="col-sm-8">
          <input type="password" class="form-control" id="Password" name="Password"
                 placeholder="Password">
        </div>
      </div>

      <br>
      <div class="form-group row">
        <div class="col-sm-12">
          <button type="submit" class="btn btn-success btn-lg btn-block">Save</button>
        </div>
      </div>
    </form>

    <br>
    <p class="text-center">
      Copyright &copy; SEED LABs
    </p>
  </div>

  <script type="text/javascript">
    function logout(){
      location.href = "logoff.php";
    }
  </script>
</body>
</html>
