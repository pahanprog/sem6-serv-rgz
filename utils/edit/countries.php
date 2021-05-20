<?php
    require('../connect.php');

    $countriesSql = "SELECT Name FROM Country";

    if (!$countries = $conn->query($countriesSql)) {
        print_r($conn->error);
    } else {
        $countries = $countries->fetch_all();
        echo json_encode($countries);
    }
?>