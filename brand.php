<!DOCTYPE html>
<html lang="en">

<?php
    session_start();
    require('./utils/connect.php');
    require('./utils/search/brands.php');
    require('./utils/isLoggedIn.php');

    $brandname = $value = $country = $year = $blob = "";
    $models = null;
    $new = false;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT BrandName, FoundationYear, CompanyValue, country.Name as Country, Image FROM `brand` INNER JOIN country ON CountryId = country.Id WHERE brand.Id = $id";

        if (!$result = $conn->query($sql)) {
            print_r($conn->error);
        } else {
            $result = $result->fetch_assoc();
            if (!isset($result['BrandName'])) {
                echo "<script>window.location.href='./';</script>";
            }
            $brandname = $result['BrandName'];
            $value = intval($result['CompanyValue']);
            $value = number_format($value);
            $country = $result['Country'];
            $year = $result['FoundationYear'];
            $blob = $result['Image'];
        }

        if ($_SESSION['role'] == 1) {
            $addview = "UPDATE brand SET Views = Views + 1 WHERE id = $id";
        
            if (!$addedviews = $conn->query($addview)) {
                print_r($conn->error);
            }
        }

        $sql2 = "SELECT model.Name, model.CarBody, model.Year, model.Image, model.Id as Id FROM brand INNER JOIN model ON model.BrandId = brand.Id WHERE brand.Id = $id";

        if (!$models = $conn->query($sql2)) {
            print_r($conn->error);
        }
    } elseif (isset($_GET['new'])) {
        if ($_SESSION['role'] == 4) {
            $new = true;
        } else {
            echo "<script>window.location.href='./';</script>";
        }
    } else {
        echo "<script>window.location.href='./';</script>";
    }

    if (isset($_POST['delete'])) {
        $conn->begin_transaction();
        try {
            $conn->query("DELETE model FROM model JOIN brand ON brand.id = model.brandId WHERE brand.id=$id");
            $conn->query("DELETE FROM brand WHERE id=$id");
            $conn->commit();
            echo "<script>window.location.href='./';</script>";
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            throw $exception;
        }
    }
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$brandname?></title>
    <link rel="stylesheet" href="./static/styles.css">
    <script src="./js/searchHandler.js" defer></script>
    <?php
        include('./utils/livereload.php');
    ?>
</head>

<body>
    <?php
        if ($_SESSION['role']  == 4 ):
    ?>
    <script>
    let edit = false;
    let newbool = false;
    const initial = {};
    let countries = [];
    let saveBtn;
    let imgEdit;
    let deleteBtn;
    let editBtn;

    document.addEventListener("DOMContentLoaded", () => {
        saveBtn = document.getElementById('save-btn')
        imgEdit = document.getElementById('edit-img')
        editBtn = document.getElementById('edit-btn')
        const elements = document.querySelectorAll('[data-edit]');
        elements.forEach((el) => {
            initial[el.dataset.edit] = el.innerHTML;
        });
        var url_string = window.location.href
        var url = new URL(url_string);
        newbool = url.searchParams.get("new");
        if (newbool) {
            handleEditChange();
            editBtn.classList.add('invisible')
        } else {
            deleteBtn = document.getElementById('edit-delete')
        }
    });

    const handleEditChange = () => {
        const elements = document.querySelectorAll('[data-edit]');
        edit = !edit;
        if (edit) {
            saveBtn.classList.remove("invisible");
            imgEdit.classList.remove("invisible");
            if (!newbool) {
                deleteBtn.classList.remove("invisible");
            }
            elements.forEach((el) => {
                const parent = el.parentNode;
                if (el.dataset.input == "input") {
                    const input = document.createElement('input');
                    input.type = el.dataset.type;
                    if (input.type == "number") {
                        input.value = el.innerHTML.replace(/,| |\$/g, '');
                    } else {
                        input.value = el.innerHTML;
                    }
                    if (el.dataset.edit == "brandname") input.classList.add("h1");
                    input.name = el.dataset.edit;
                    input.dataset.edit = el.dataset.edit;
                    input.dataset.type = el.dataset.type;
                    input.dataset.input = el.dataset.input;
                    parent.replaceChild(input, el);
                } else if (el.dataset.input == "select") {
                    const select = document.createElement('select');
                    select.name = el.dataset.edit;
                    select.dataset.edit = el.dataset.edit;
                    select.dataset.input = el.dataset.input;
                    if (countries.length == 0) {
                        fetch('./utils/edit/countries.php', {
                            method: "POST"
                        }).then(async (data) => {
                            countries = await data.json();
                            countries.forEach((country) => {
                                const option = document.createElement('option');
                                option.innerHTML = country[0];
                                option.value = country[0];
                                if (option.value == el.innerHTML) option.selected =
                                    true;
                                select.appendChild(option);
                            })
                        })
                    } else {
                        countries.forEach((country) => {
                            const option = document.createElement('option');
                            option.innerHTML = country[0];
                            option.value = country[0];
                            if (option.value == el.innerHTML) option.selected =
                                true;
                            select.appendChild(option);
                        })
                    }
                    parent.replaceChild(select, el);
                }
            })
        } else {
            saveBtn.classList.add("invisible");
            imgEdit.classList.add("invisible");
            if (!newbool) {
                deleteBtn.classList.add("invisible");
            }
            elements.forEach((el) => {
                const parent = el.parentNode;
                let span;
                if (el.dataset.edit == "brandname") {
                    span = document.createElement('h1');
                } else {
                    span = document.createElement('span');
                }
                span.innerHTML = initial[el.dataset.edit];
                span.dataset.edit = el.dataset.edit;
                span.dataset.input = el.dataset.input;
                if (span.dataset.input == "input") span.dataset.type = el.dataset.type;
                parent.replaceChild(span, el);
            })
        }
    }

    const handleEditSubmit = async () => {
        const imginput = document.getElementById('img-input');
        const brandname = document.querySelectorAll('[data-edit="brandname"]')[0].value;
        var url_string = window.location.href
        var url = new URL(url_string);
        var id = url.searchParams.get("id");
        const form = document.getElementById('edit-form');
        const formdata = new FormData(form);
        !id ? formdata.append('new', true) : formdata.append('new', false)
        formdata.append('id', id);
        formdata.append('brandname', brandname);

        if (imginput.files[0]) {
            const data = await new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(imginput.files[0]);
                reader.onload = () => resolve(reader.result);
                reader.onerror = (error) => reject(error);
            })
            formdata.append('imgdata', data);
        }

        fetch('./utils/updateBrand.php', {
            method: 'POST',
            body: formdata
        }).then(async (data) => {
            const json = await data.json();
            if (json.success) {
                if (id) {
                    window.location.href = `./brand.php?id=${id}`
                } else {
                    history.replaceState({}, "", './')
                    window.location.href = './'
                }
            } else {
                if (json.error) {
                    alert(json.error);
                }
            }
        })
    }
    </script>
    <?php
        endif;
        include("./components/header.php")
    ?>

    <main class="brand">
        <div class="search__container">
            <form onsubmit="return onSubmit()" name="search">
                <div class="search__heading">
                    Find your car
                </div>

                <select name="brand" id="brand" onchange="handleSelectChange(this)">
                    <option value="dis" disabled selected>
                        Select a brand
                    </option>
                    <?php
                        $temp = $brands->fetch_all();
                        foreach ($temp as $row) {
                            echo "<option value=\"" . $row[0] . "\">" . $row[0] . "</option>";
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
                        Select a year
                    </option>
                </select>
                <button type="submit">Go!</button>
            </form>
        </div>
        <div class="brand__container">
            <div class="brand__heading">
                <div class="img__info">
                    <?php
                        if (!$new) :
                    ?>
                    <?php
                    if ($blob) {
                        echo "<img src=\"data:image/gif;base64," . $blob ."\">";
                    } else {
                        echo "<img src=\"./images/noimage.jpg\">";
                    }
                    ?>
                    <?php
                        endif;
                    ?>
                    <div class="brand__info">
                        <div class="brandname__container">
                            <?php
                            if ($new) :
                        ?>
                            <div>Brand Name:</div>
                            <?php
                            endif;
                        ?>
                            <h1 data-edit="brandname" data-input="input" data-type="text"><?=$brandname?></h1>

                        </div>
                        <form id="edit-form">
                            <div>
                                Year of foundation:
                                <span data-edit="year" data-input="input" data-type="number"><?=$year?></span>
                            </div>
                            <div>
                                Company Value:
                                <span data-edit="value" data-input="input" data-type="number"><?=$value?> $</span>
                            </div>
                            <div>
                                Country:
                                <span data-edit="country" data-input="select"><?=$country?></span>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                    if ($_SESSION['role'] == 4) :
                ?>
                <div class="admin__options">
                    <div class="edit__brand">
                        <span class="btn edit" id='edit-btn' onclick="handleEditChange()">Edit</span>
                        <span class="btn save invisible" id='save-btn' onclick="handleEditSubmit()">Save</span>
                    </div>
                    <div class="edit__img invisible" id="edit-img">
                        <label for="car-img">Choose new brand image to upload</label>
                        <input type="file" name="car-img" id="img-input" accept=".jpg, .jpeg, .png" />
                    </div>
                    <div class="edit__delete invisible" id='edit-delete'>
                        <form method="post">
                            <button type="submit" name="delete">Delete</button>
                        </form>
                    </div>
                </div>
                <?php
                    endif;
                ?>
            </div>
            <div class="brand__models">
                <?php
                    if ($models) { 
                        while($row = $models->fetch_assoc()) {
                            $model_link = $brandname . " " . $row['Name'] . " " . $row['CarBody'] . " " . $row['Year'];
                            if (isset($row['Image'])) {
                                echo "<div class=\"brand__model\"><a href=\"./model.php?id=" . $row['Id'] . "\"><img src=\"data:image/gif;base64," . $row['Image'] . "\"/><div>" . $model_link . "</div></a></div>";
                            } else {
                                echo "<div class=\"brand__model\"><a href=\"./model.php?id=" . $row['Id'] . "\"><img src=\"./images/noimage.jpg\"/><div>" . $model_link . "</div></a></div>";
                            }
                        }
                    }
                ?>
                <?php
                    if($_SESSION['role'] == 4 && !$new) :
                ?>
                <div class="brand__model">
                    <a href="./model.php?new=true&brandname=<?=$brandname?>&brandid=<?=$id?>">
                        <img src="./images/plus.png" />
                        <div>
                            Add a new model
                        </div>
                    </a>
                </div>
                <?php
                    endif;
                ?>
            </div>
        </div>
    </main>
    <?php
        include('./components/footer.php');
    ?>
</body>

</html>