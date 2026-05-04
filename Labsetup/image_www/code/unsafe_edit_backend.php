<!--
SEED Lab: SQL Injection Education Web plateform
Author: Kailiang Ying
Email: kying@syr.edu
-->
<!--
SEED Lab: SQL Injection Education Web plateform
Enhancement Version 1.
Date: 10th April 2018.
Developer: Kuber Kohli.

Update: The password was stored in the session was updated when password is changed.
-->

<!DOCTYPE html>
<html>
<body>

<?php
session_start();

$input_email       = $_GET['Email'] ?? '';
$input_nickname    = $_GET['NickName'] ?? '';
$input_address     = $_GET['Address'] ?? '';
$input_pwd         = $_GET['Password'] ?? '';
$input_phonenumber = $_GET['PhoneNumber'] ?? '';

$uname = $_SESSION['name'] ?? '';
$eid   = $_SESSION['eid'] ?? '';
$id    = $_SESSION['id'] ?? '';

function getDB() {
  $dbhost  = "oracle";     // docker-compose service name recommended
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

$conn = getDB();

/**
 * UNSAFE on purpose for the lab:
 * We concatenate user inputs directly into SQL.
 * (This is intentionally vulnerable like the MySQL version.)
 */
$sql = "";

if ($input_pwd !== '') {
  $hashed_pwd = sha1($input_pwd);
  $_SESSION['pwd'] = $hashed_pwd;

  
  $sql = "UPDATE credential SET nickname='$input_nickname', email='$input_email', address='$input_address', Password='$hashed_pwd', PhoneNumber='$input_phonenumber' WHERE ID=$id";

} else {
    $sql = "UPDATE credential SET nickname='$input_nickname', email='$input_email', address='$input_address', PhoneNumber='$input_phonenumber' WHERE ID=$id";
}
 

$stid = oci_parse($conn, $sql);
if (!$stid) {
  $e = oci_error($conn);
  die("Parse failed: " . htmlentities($e['message']));
}

$ok = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
if (!$ok) {
  $e = oci_error($stid);
  die("Execute failed: " . htmlentities($e['message']));
}

oci_free_statement($stid);
oci_close($conn);

header("Location: unsafe_home.php");
exit();
?>

</body>
</html>
