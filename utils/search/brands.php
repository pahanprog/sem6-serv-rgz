<?php
    $sqlbrands = "SELECT BrandName FROM brand";

    if (!$brands = $conn->query($sqlbrands)) {
        print_r($conn->error);
    }
?>