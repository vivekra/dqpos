<?php
require "ost-config.php";
$conn=mysqli_connect("localhost","dquser","abc123") or die("Could not connect");
mysqli_select_db($conn,"dqst") or die("could not connect database");
?>
