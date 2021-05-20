<!DOCTYPE html>
<html lang="en">

<?php
    require('./utils/connect.php');
    require('./utils/search/brands.php');
    require('./utils/isLoggedIn.php');


    $modelname = $modelheader = $price = $blob = $body = $seats = $year = $drive = $engine = $speed = $acc = $brandid = "";
    $new = false;
    

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT brand.BrandName ,model.Name, brandId, CarBody, Year, Seats, Price, drive.Name as Drive ,EngineType, TopSpeed, Acceleration, model.Image, brand.FoundationYear, brand.CompanyValue, country.Name as Country FROM model INNER JOIN drive ON DriveId = drive.Id INNER JOIN brand ON BrandId = brand.Id INNER JOIN country ON brand.CountryId = country.Id WHERE model.Id = $id";

        if (!$result = $conn->query($sql)) {
            print_r($conn->error);
        } else {
            $result = $result->fetch_assoc();
            if (!isset($result['Name'])) {
                echo "<script>window.history.go(-1);</script>";
            }
            $modelname = $result['Name'];
            $modelheader = $result['BrandName'] . " <span data-edit='modelname' data-input='input' data-type='text'>" . $result['Name'] . "</span> " . $result['Year'];
            $price = intval($result['Price']);
            $price = number_format($price);
            $blob = $result['Image'];
            $body = $result['CarBody'];
            $seats = $result['Seats'];
            $year = $result['Year'];
            $drive = $result['Drive'];
            $engine = $result['EngineType'];
            $speed = $result['TopSpeed'];
            $acc = $result['Acceleration'];
            $brandname = $result['BrandName'];
            $country = $result['Country'];
            $foundationyear = $result['FoundationYear'];
            $comvalue = intval($result['CompanyValue']);
            $comvalue = number_format($comvalue);
            $brandid = $result['brandId'];
        }

        if ($_SESSION['role'] == 1) {
            $addview = "UPDATE model SET Views = Views + 1 WHERE id = $id";
        
            if (!$addedviews = $conn->query($addview)) {
                print_r($conn->error);
            }
        }
    } elseif (isset($_GET['new'])) {
        if ($_SESSION['role'] == 4) {
            $new = true;
            $brandname = $_GET['brandname'];
            $modelheader = "Adding new model to $brandname";
        } else {
            echo "<script>window.history.go(-1);</script>";
        }
    } else {
        echo "<script>window.history.go(-1);</script>";
    }
    if (isset($_POST['delete'])) {
        $conn->begin_transaction();
        try {
            $conn->query("DELETE FROM model WHERE id=$id");
            $conn->commit();
            echo "<script>window.location.href='./brand.php?id=". $brandid ."';</script>";
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            throw $exception;
        }
    }

    $sqldrive = "SELECT Name FROM Drive";

    if (!$drives = $conn->query($sqldrive)) {
        print_r($conn->error);
    } 
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$modelname?></title>
    <link rel="stylesheet" href="./static/styles.css">
    <script src="./js/searchHandler.js" defer></script>
    <?php
        include('./utils/livereload.php');
    ?>
</head>

<body>
    <script>
    let edit = false;
    let newbool = false;
    const initial = {};
    let brands = [];
    let drives = [];
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
            elements.forEach(async (el) => {
                const parent = el.parentNode;
                if (el.dataset.input == "input") {
                    const input = document.createElement('input');
                    input.type = el.dataset.type;
                    if (input.type == "number") {
                        input.value = el.innerHTML.replace(/(,| |\$|(km\/h)|s)+/g, '');
                    } else {
                        input.value = el.innerHTML;
                    }
                    if (el.dataset.edit == "modelname") input.classList.add('h1');
                    input.name = el.dataset.edit;
                    input.placeholder = el.dataset.placeholder;
                    input.dataset.edit = el.dataset.edit;
                    input.dataset.type = el.dataset.type;
                    input.dataset.input = el.dataset.input;
                    input.dataset.placeholder = el.dataset.placeholder;
                    parent.replaceChild(input, el);
                } else if (el.dataset.input == "select") {
                    const select = document.createElement('select');
                    select.name = el.dataset.edit;
                    select.dataset.edit = el.dataset.edit;
                    select.dataset.input = el.dataset.input;
                    if (select.dataset.edit == "drive") {
                        if (drives.length == 0) {
                            await fetch('./utils/edit/drives.php', {
                                method: "POST"
                            }).then(async (data) => {
                                drives = await data.json();
                                drives.forEach((country) => {
                                    const option = document.createElement('option');
                                    option.innerHTML = country[0];
                                    option.value = country[0];
                                    if (option.value == el.innerHTML) option
                                        .selected =
                                        true;
                                    select.appendChild(option);
                                })
                            })
                        } else {
                            drives.forEach((drive) => {
                                const option = document.createElement('option');
                                option.innerHTML = drive[0];
                                option.value = drive[0];
                                if (option.value == el.innerHTML) option.selected =
                                    true;
                                select.appendChild(option);
                            })
                        }
                    } else if (select.dataset.edit == "brandname") {
                        if (brands.length == 0) {
                            await fetch('./utils/edit/brands.php', {
                                method: "POST"
                            }).then(async (data) => {
                                brands = await data.json();
                                brands.forEach((brand) => {
                                    const option = document.createElement('option');
                                    option.innerHTML = brand[0];
                                    option.value = brand[0];
                                    if (option.value == el.innerHTML) option
                                        .selected =
                                        true;
                                    select.appendChild(option);
                                })
                            })
                        } else {
                            brands.forEach((brand) => {
                                const option = document.createElement('option');
                                option.innerHTML = brand[0];
                                option.value = brand[0];
                                if (option.value == el.innerHTML) option.selected =
                                    true;
                                select.appendChild(option);
                            })
                        }
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
                const span = document.createElement('span');
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
        const modelname = document.querySelectorAll('[data-edit="modelname"]')[0].value;
        var url_string = window.location.href
        var url = new URL(url_string);
        var id = url.searchParams.get("id");
        const form = document.getElementById('edit-form');
        const formdata = new FormData(form);
        !id ? formdata.append('new', true) : formdata.append('new', false)
        formdata.append('id', id);
        formdata.append('modelname', modelname);

        if (imginput.files[0]) {
            const data = await new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(imginput.files[0]);
                reader.onload = () => resolve(reader.result);
                reader.onerror = (error) => reject(error);
            })
            formdata.append('imgdata', data);
        }

        fetch('./utils/updateModel.php', {
            method: 'POST',
            body: formdata
        }).then(async (data) => {
            const json = await data.json();
            if (json.success) {
                if (id) {
                    window.location.href = `./model.php?id=${id}`
                } else {
                    const brandid = url.searchParams.get("brandid");
                    history.replaceState({}, "", `./brand.php?id=${brandid}`)
                    window.location.href = `./brand.php?id=${brandid}`
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
            include("./components/header.php")
        ?>

    <main class="model">
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
        <div class="model__container">
            <div class="model__heading">
                <h1><?=$modelheader?></h1>
            </div>
            <div class="model__info">
                <form action="POST" id='edit-form'>
                    <div class="specs">
                        <div class="seperator">
                            General
                        </div>
                        <div class="rows">
                            <?php
                                if ($new) :
                            ?>
                            <div>
                                Model name:
                            </div>
                            <input data-edit="modelname" type="text" name="modelname" value='<?=$modelname?>'
                                placeholder="New model name" />
                            <input style="display:none" name="brandname" value='<?=$brandname?>' />
                            <?php
                                endif;
                            ?>
                            <div>
                                Price:
                            </div>
                            <span data-edit="price" data-input="input" data-type="number"
                                data-placeholder="New price"><?=$price?> $</span>
                            <div>
                                Car Body:
                            </div>
                            <span data-edit="body" data-input="input" data-type="text"
                                data-placeholder="New car body"><?=$body?></span>
                            <div>
                                Number of seats:
                            </div>
                            <span data-edit="seats" data-input="input" data-type="number"
                                data-placeholder="New number of seats"><?=$seats?></span>
                            <div>
                                First year of production:
                            </div>
                            <span data-edit="year" data-input="input" data-type="number"
                                data-placeholder="New first year of production"><?=$year?></span>
                        </div>
                        <div class="seperator">
                            Drive
                        </div>
                        <div class="rows">
                            <div>
                                Drive:
                            </div>
                            <span data-edit="drive" data-input="select"><?=$drive?></span>
                            <div>
                                Engine type:
                            </div>
                            <span data-edit="engine" data-input="input" data-type="text"
                                data-placeholder="New engine type"><?=$engine?></span>
                        </div>
                        <div class="seperator">
                            Performance
                        </div>
                        <div class="rows">
                            <div>
                                Top Speed:
                            </div>
                            <span data-edit="speed" data-input="input" data-type="number"
                                data-placeholder="New top speed"><?=$speed?> km/h</span>
                            <div>
                                Acceleration 0-100 km/h:
                            </div>
                            <span data-edit="acceleration" data-input="input" data-type="number"
                                data-placeholder="New ccceleration"><?=$acc?> s</span>
                        </div>
                        <?php
                            if (!$new) :
                        ?>
                        <div class="seperator">
                            Brand
                        </div>
                        <div class="rows">
                            <div>
                                Brand Name:
                            </div>
                            <span data-edit="brandname" data-input="select"><?=$brandname?></span>
                            <div>
                                Country:
                            </div>
                            <span><?=$country?></span>
                            <div>
                                Foundation year:
                            </div>
                            <span><?=$foundationyear?></span>
                            <div>
                                Company value:
                            </div>
                            <span><?=$comvalue?> $</span>
                        </div>
                        <?php
                            endif;
                        ?>

                    </div>
                </form>

                <div class="img">
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

                    <?php
                        if ($_SESSION['role'] == 4) :
                    ?>
                    <div class="admin__options">
                        <div class="edit__specs">
                            <span class="btn edit" onclick="handleEditChange()" id='edit-btn'>Edit</span>
                            <span class="btn save invisible" id="save-btn" onclick="handleEditSubmit()">Save</span>
                        </div>
                        <div class="edit__img invisible" id="edit-img">
                            <label for="car-img">Choose new car image to upload</label>
                            <input type="file" name="car-img" id="img-input" accept=".jpg, .jpeg, .png .webp" />
                        </div>
                        <?php
                            if (!$new) :
                        ?>
                        <div class="edit__delete invisible" id='edit-delete'>
                            <form method="post">
                                <button type="submit" name="delete">Delete</button>
                            </form>
                        </div>
                        <?php
                            endif;
                        ?>
                    </div>
                    <?php
                    endif;
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