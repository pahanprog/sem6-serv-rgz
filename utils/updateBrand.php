<?php
    require('./connect.php');

    $imgdata = null;

    $id=$_POST['id'];
    $new = $_POST['new'];

    $year = $_POST['year'];
    $value = $_POST['value'];
    $value = str_replace(',', '', $value);
    $country = $_POST['country'];
    $brandname = $_POST['brandname'];

    if (isset($_POST['imgdata'])) {
        $imgdata = $_POST['imgdata'];
        $pos = strpos($imgdata, 'base64,');
        $blobData = substr($imgdata, $pos + 7);
    }

    $countryidsql = "SELECT Id FROM Country WHERE Name='$country'";

    if (!$countryid = $conn->query($countryidsql)) {
        die ($conn->error);
    }

    $countryid = $countryid->fetch_assoc()['Id'];

    if (!$imgdata) {
        if ($new === "true") {
            $sql = "INSERT INTO brand(BrandName, CountryId, FoundationYear, CompanyValue) VALUES ('$brandname', $countryid, $year, $value)";
        } else {
            $sql = "UPDATE brand SET BrandName = '$brandname', CountryId = $countryid, FoundationYear = $year, CompanyValue = $value WHERE Id= $id";
        }
    } else {
        if ($new === "true") {
            $sql = "INSERT INTO brand(BrandName, CountryId, FoundationYear, CompanyValue, Image) VALUES ('$brandname', $countryid, $year, $value, '$blobData')";
        } else {
            $sql = "UPDATE brand SET Brandname = '$brandname', CountryId = $countryid, FoundationYear = $year, CompanyValue=$value, Image = '$blobData' WHERE Id=$id";
        }
    }

    if (!$result = $conn->query($sql)) {
        echo '{"success": false, "error":"'. $conn->error . '"}';
    } else {
        echo '{"success": true}';
    }
?>