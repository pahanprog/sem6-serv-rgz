<?php
    require('../connect.php');

    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);

    $model = $data['model'];

    $sql = "SELECT Year FROM model WHERE Name = '$model'";

    if (!$years = $conn->query($sql)) {
        print_r($conn->error);
    } else {
        echo json_encode($years->fetch_all());
    }
?>