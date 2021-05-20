<?php
    require('../connect.php');

    $drivesSql = "SELECT Name FROM Drive";

    if (!$drives = $conn->query($drivesSql)) {
        print_r($conn->error);
    } else {
        $drives = $drives->fetch_all();
        echo json_encode($drives);
    }
?>