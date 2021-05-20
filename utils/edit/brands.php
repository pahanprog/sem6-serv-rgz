<?php
    require('../connect.php');

    $brandsSql = "SELECT BrandName FROM Brand";

    if (!$brands = $conn->query($brandsSql)) {
        print_r($conn->error);
    } else {
        $brands = $brands->fetch_all();
        echo json_encode($brands);
    }
?>