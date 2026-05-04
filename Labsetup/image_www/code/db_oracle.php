<?php
// image_www/Code/db_oracle.php
// Central Oracle DB helper used by all pages.

function db_connect_oracle() {
    $dbhost  = "10.9.0.7";
    $dbport  = "1521";
    $service = "FREEPDB1";
    $dbuser  = "seed";
    $dbpass  = "dees";

    $connStr = "//{$dbhost}:{$dbport}/{$service}";
    $conn = oci_connect($dbuser, $dbpass, $connStr, "AL32UTF8");
    if (!$conn) {
        $e = oci_error();
        die("Oracle connection failed: " . $e['message']);
    }
    return $conn;
}

function db_query_oracle($conn, $sql) {
    $stid = oci_parse($conn, $sql);
    if (!$stid) {
        $e = oci_error($conn);
        die("Oracle parse failed: " . $e['message']);
    }
    $ok = oci_execute($stid);
    if (!$ok) {
        $e = oci_error($stid);
        die("Oracle execute failed: " . $e['message']);
    }
    return $stid;
}
?>
