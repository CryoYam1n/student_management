<?php
$conn = new mysqli("localhost", "root", "", "blaze");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Do not echo or close the connection here
?>