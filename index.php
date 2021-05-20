<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./js/searchHandler.js" defer></script>
    <title>Main page</title>
    <link rel="stylesheet" href="./static/styles.css">
    <?php
    include('./utils/livereload.php');
    ?>
</head>

<body>
    <?php
        require('./utils/connect.php');
        require('./utils/isLoggedIn.php');

        $brands = $models = $years = null;

        require('./utils/search/brands.php');
    ?>
    <?php
        include("./components/header.php")
    ?>
    <main class="index">
        <div class="search__container">
            <form onsubmit="return onSubmit()" name="search" class="index">
                <div class="search__heading">
                    Find your car
                </div>

                <select name="brand" id="brand" onchange="handleSelectChange(this)">
                    <option value="dis" disabled selected>
                        Select a brand
                    </option>
                    <?php
                        while ($row = $brands->fetch_assoc()) {
                            echo "<option value=\"" . $row['BrandName'] . "\">" . $row['BrandName'] . "</option>";
                        }
                    ?>
                </select>
                <select name="model" id="model" onchange="handleSelectChange(this)">
                    <option value="dis" disabled selected>
                        Select a model
                    </option>
                </select>
                <select name="year" id="year" onchange="handleSelectChange(this)">
                    <option value="dis" disabled selected>
                        Select year of production
                    </option>
                </select>
                <button type="submit">Go!</button>
            </form>
        </div>
        <div class="brands">
            <?php 
                    $sql = "SELECT BrandName, Id, Image FROM `brand`";

                    if (!$result = $conn->query($sql)) {
                        print_r($conn->error);
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            if (isset($row['Image'])) {
                                echo "<div class=\"brand__preview\"><a class=\"brand__link\" href=\"/rgz/brand.php?id=". $row['Id']. "\"><img src=\"data:image/gif;base64," . $row['Image'] . "\"/><div class=\"link\">" . $row['BrandName'] . "</div></a></div>";
                            } else {
                                echo "<div class=\"brand__preview\"><a class=\"brand__link\" href=\"/rgz/brand.php?id=". $row['Id']. "\"><img src=\"./images/noimage.jpg\"/><div class=\"link\">" . $row['BrandName'] . "</div></a></div>";

                            }
                        }
                    }
                ?>
            <?php
                    if($_SESSION['role'] == 4) :
                ?>
            <div class="brand__preview">
                <a class="brand__link" href="./brand.php?new=true">
                    <img class='brand__img' src='./images/plusbrand.png' />
                    <div class="link">Add new brand</div>
                </a>
            </div>
            <?php
                    endif;
                ?>
        </div>
        <div class="most__viewed">
            <div>
                <div class="most__heading">
                    Most viewed brands
                </div>
                <div class="most__brands">
                    <?php
                    $brandMostSql = "SELECT BrandName, Id FROM brand ORDER BY Views DESC LIMIT 10";

                    if (!$brandsmost = $conn->query($brandMostSql)) {
                        print_r($conn->error);
                    }else {
                        while ($row = $brandsmost->fetch_assoc()) {
                            echo "<a href=\"/rgz/brand.php?id=" . $row['Id'] ."\">" . $row['BrandName'] . "</a>";
                        }
                    }
                ?>
                </div>
            </div>
            <div>
                <div class="most__heading">
                    Most viewed models
                </div>
                <div class="most__models">
                    <?php
                    $modelMostSql = "SELECT Name, model.Id, BrandName FROM model INNER JOIN brand ON BrandId = brand.Id ORDER BY model.Views DESC LIMIT 10";

                    if (!$modelsmost = $conn->query($modelMostSql)) {
                        print_r($conn->error);
                    }else {
                        while ($row = $modelsmost->fetch_assoc()) {
                            echo "<a href=\"/rgz/model.php?id=" . $row['Id'] ."\">" . $row['BrandName'] . " " . $row['Name'] . "</a>";
                        }
                    }
                ?>
                </div>
            </div>
        </div>
    </main>
    <?php
        include('./components/footer.php');
    ?>
</body>

</html>