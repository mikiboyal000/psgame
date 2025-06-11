<?php
$con = mysqli_connect("localhost","shribala_tvlion","tvlion12340","shribala_vicky_game_db");

// Check connection
if (mysqli_connect_errno())
  {
      
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
session_start();
?>