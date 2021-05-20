<?php
    require('./connect.php');

    $imgdata = null;

    $id=$_POST['id'];
    $new = $_POST['new'];

    $modelname = $_POST['modelname'];

    $price = $_POST['price'];
    $price = str_replace(',', '', $price);
    $body = $_POST['body'];
    $seats = $_POST['seats'];
    $year = $_POST['year'];

    $drive = $_POST['drive'];
    $engine = $_POST['engine'];

    $speed = $_POST['speed'];
    $acceleration = $_POST['acceleration'];
    $brandname = $_POST['brandname'];

    if (isset($_POST['imgdata'])) {
        $imgdata = $_POST['imgdata'];
        $pos = strpos($imgdata, 'base64,');
        $blobData = substr($imgdata, $pos + 7);
    }

    $driveidsql = "SELECT Id FROM drive WHERE name='$drive'";

    if (!$driveid = $conn->query($driveidsql)) {
        die ($conn->error);
    }

    $driveid = $driveid->fetch_assoc()['Id'];

    $brandidsql = "SELECT Id FROM brand WHERE BrandName='$brandname'";

    if (!$brandid = $conn->query($brandidsql)) {
        die ($conn->error);
    }

    $brandid = $brandid->fetch_assoc()['Id'];
    
    if (!$imgdata) {
        if ($new === "true") {
            $sql = "INSERT INTO model(Name, CarBody, Year, BrandId, Price, Seats, DriveId, EngineType, TopSpeed, Acceleration) VALUES ('$modelname', '$body', $year, $brandid, $price, $seats, $driveid, '$engine', $speed, $acceleration)";
        } else {
            $sql = "UPDATE model SET Name='$modelname', CarBody = '$body', Price=$price, Seats=$seats, Year=$year, DriveId=$driveid, EngineType='$engine', TopSpeed=$speed, Acceleration=$acceleration, BrandId=$brandid WHERE Id=$id";
        }
    } else {
        if ($new === "true") {
            $sql = "INSERT INTO model(Name, CarBody, Year, BrandId, Price, Seats, DriveId, EngineType, TopSpeed, Acceleration, Image) VALUES ('$modelname', '$body', $year, $brandid, $price, $seats, $driveid, '$engine', $speed, $acceleration, '$blobData')";
        } else {
            $sql = "UPDATE model SET Name='$modelname', Price=$price, Seats=$seats, Year=$year, DriveId=$driveid, EngineType='$engine', TopSpeed=$speed, Acceleration=$acceleration, BrandId=$brandid, Image='$blobData' WHERE Id=$id";
        }
    }
    
    if (!$result = $conn->query($sql)) {
        echo '{"success": false, "error":"'. $conn->error . '"}';
    } else {
        echo '{"success": true}';
    }
?>