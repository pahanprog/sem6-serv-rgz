<?php
    $server = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "rgzv2";
    
    $conn = new mysqli($server, $username, $password, $dbname);
        
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>