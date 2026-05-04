<?php
// image_www/Code/db_oracle.php

function oracle_connect() {
    // Keep same variable names style as existing code where possible
    $dbhost = "10.9.0.6";        // oracle container IP
    $dbport = "1521";
    $service = "FREEPDB1";       // Oracle service/PDB
    $dbuser = "seed";
    $dbpass = "dees";

    // Easy Connect string
    $connStr = "//{$dbhost}:{$dbport}/{$service}";

    $conn = oci_connect($dbuser, $dbpass, $connStr, "AL32UTF8");
    if (!$conn) {
        $e = oci_error();
        die("Oracle connection failed: " . $e['message']);
    }
    return $conn;
}

// Run a query and return the statement handle
function oracle_query($conn, $sql) {
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
