<?php
  $conn = new mysqli("localhost", "root", "", "blaze");
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } else {
      echo "Connection successful!";
  }
  $conn->close();
  ?>