<?php
    require('../connect.php');

    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);

    $brand = $data['brand'];

    $sql = "SELECT DISTINCT  Name FROM model INNER JOIN brand ON brand.Id = model.BrandId WHERE BrandName = '$brand'";

    if (!$models = $conn->query($sql)) {
        print_r($conn->error);
    } else {
        echo json_encode($models->fetch_all());
    }
?>