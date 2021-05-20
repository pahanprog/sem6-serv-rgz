<!DOCTYPE html>
<html lang="en">

<?php
    require('./utils/connect.php');
    require('./utils/isLoggedIn.php');

    $emailerr = $usernameerr = $msg = $password = $passerr = null;

    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $role = ($_SESSION['role'] == 4) ? 'Admin' : 'User';

    $oldusername = $username;


    if (isset($_POST['username'])) {
        $regexE = "/^[a-z0-9\.-_]+@[a-z0-9]+\.[a-z]{2,3}/";
        $email = htmlspecialchars($_POST["email"]);
            if (!preg_match($regexE, $email)) {
                $emailerr = "Invalid email";
        }

        $regexU = "/[a-z0-9]{6,}/";
        $username = htmlspecialchars($_POST["username"]);
        if (!preg_match($regexU, $username)) {
            $usernameerr = "Invalid username";
        }

        $regexP = "/(?=.{8,})(?=.*[a-z])(?=.*[A-Z])/";
        $password = htmlspecialchars($_POST["password"]);
        if ($password != "") {
            if (!preg_match($regexP, $password)) {
                $passerr = "Invalid password";
            }
        }

        if (!$emailerr && !$usernameerr) {
            if (!$passerr && $password != "") {
                $hashedpassword = password_hash($password, PASSWORD_ARGON2I);
                $sql = "UPDATE users SET PasswordHash = '$hashedpassword', Username='$username', Email='$email' WHERE Username = '$oldusername'";
            } else {
                $sql = "UPDATE users SET Username='$username', Email='$email' WHERE Username = '$oldusername'";
            }
            $sql2 = "SELECT Email, Username, UserStatus FROM users WHERE Username = '$username'";

            if (!$conn->query($sql)) {
                if (str_contains($conn->error,"'Email_un'")) {
                    $emailerr = "Email already exists";
                } elseif (str_contains($conn->error,"'Username_un'")) {
                    $usernameerr = "Username already exists";
                }
            } elseif (!$result = $conn->query($sql2)) {
                print_r($conn->error);
            } else {
                $result = $result->fetch_assoc();
                $_SESSION['username'] = $result['Username'];
                $_SESSION['role'] = $result['UserStatus'];
                $_SESSION['email'] = $result['Email'];
                if (!$passerr && $password != "") {
                    session_destroy();
                    echo "<script>window.location.href='./login.php';</script>";
                }
                $msg = "Successfully saved changes";
            }
            
        }
    }

    if (isset($_POST['delete'])) {

        $sql = "DELETE FROM users WHERE Username = '$username'";

        if (!$conn->query($sql)) {
            print_r($conn->error);
        } else {
            header("Location: /rgz/login.php");
        }
    }
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="./static/styles.css">
    <?php
        include('./utils/livereload.php');
    ?>
</head>

<body>
    <script>
    const toggleDeleteModal = () => {
        const modal = document.getElementById('modal-delete');

        if (modal.classList.contains('open')) {
            modal.classList.remove('open');
        } else {
            modal.classList.add('open');
        }
    }
    </script>
    <div class="modal" id="modal-delete">
        <div class="modal__content">
            <div class="modal__close" onclick="toggleDeleteModal()">
                <img src="./images/times-circle.svg" />
            </div>
            <div class="modal__heading">
                Are you sure you want to delete your account?
            </div>
            <div class="modal__options">
                <button onclick="toggleDeleteModal()">No</button>
                <form method="POST">
                    <button type="submit" name="delete">Yes</button>
                </form>
            </div>
        </div>
    </div>
    <?php
            include("./components/header.php")
        ?>
    <main class="centered">
        <div class="profile">
            <h1>Profile details</h1>
            <div class="profile__info">
                <form method="post">
                    <div class="inp">
                        <label for="username">Username:</label>
                        <div>
                            <input name="username" placeholder="New username" value="<?=$username?>" />
                            <div class="error"><?=$usernameerr?></div>
                        </div>
                    </div>
                    <div class="inp">
                        <label for="email">Email address:</label>
                        <div>
                            <input name="email" placeholder="New username" value="<?=$email?>" />
                            <div class="error"><?=$emailerr?></div>
                        </div>
                    </div>
                    <div class="inp">
                        <label for="email">New password:</label>
                        <div>
                            <input type="password" name="password" placeholder="New password" value="<?=$password?>" />
                            <div class="error"><?=$passerr?></div>
                        </div>
                    </div>
                    <div class="inp">
                        <label>User role:</label>
                        <div>
                            <?=$role?>
                        </div>
                    </div>
            </div>
            <div class="profile__actions">
                <button type="submit">Save changes</button>
                <span class="btn" onclick="toggleModal()">Logout</span>
            </div>
            </form>
            <div class="save__result">
                <div><?=$msg?></div>
            </div>
            <div class="profile__delete">
                <span class="btn" onclick="toggleDeleteModal()">Delete profile</span>
            </div>
        </div>
    </main>
    <?php
        include('./components/footer.php');
    ?>
</body>

</html>