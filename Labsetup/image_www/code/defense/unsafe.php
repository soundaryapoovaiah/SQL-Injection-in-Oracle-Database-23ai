<?php
function getDB() {
  $dbhost  = "oracle";
  $dbuser  = "seed";
  $dbpass  = "dees";
  $service = "FREEPDB1";

  $connStr = "//{$dbhost}:1521/{$service}";
  $conn = oci_connect($dbuser, $dbpass, $connStr, "AL32UTF8");
  if (!$conn) {
    $e = oci_error();
    die("Connection failed: " . $e['message']);
  }
  return $conn;
}

$input_uname = $_GET['username'] ?? '';
$input_pwd   = $_GET['Password'] ?? '';
$hashed_pwd  = strtolower(sha1($input_pwd));

$conn = getDB();
// prepared statements
$stmt = oci_parse($conn, "
  SELECT id, name, eid, salary, ssn
  FROM credential
  WHERE name = :uname AND password = :pwd
");

oci_bind_by_name($stmt, ":uname", $input_uname);
oci_bind_by_name($stmt, ":pwd",   $hashed_pwd);

$r = oci_execute($stmt);
if (!$r) {
  $e = oci_error($stmt);
  die("Execute error: " . $e['message']);
}

$row = oci_fetch_assoc($stmt);
if ($row) {
  $id     = $row['ID'];
  $name   = $row['NAME'];
  $eid    = $row['EID'];
  $salary = $row['SALARY'];
  $ssn    = $row['SSN'];
} else {
  $id = $name = $eid = $salary = $ssn = "";
}

oci_free_statement($stmt);
oci_close($conn);
?>
