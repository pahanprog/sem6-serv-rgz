<?php
    require('../connect.php');

    $brand = $_REQUEST['brand'];
    $model = $_REQUEST['model'];
    $year = $_REQUEST['year'];

    $sql = "SELECT model.Id FROM model INNER JOIN brand ON BrandId = brand.Id WHERE brand.BrandName = '$brand' AND Name = '$model' AND year = $year";

    if (!$id = $conn->query($sql)) {
        print_r($conn->error);
    } else {
        $id = $id->fetch_assoc()['Id'];
        echo '{"id":' . $id .'}';
    }
?>