<?php

$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "charging_db";

// Create connection
$db = new mysqli($db_servername, $db_username, $db_password, $db_name);
// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $dd->connect_error);
}

?>