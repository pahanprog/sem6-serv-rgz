<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./static/styles.css">
    <?php
    include('./utils/livereload.php');
    ?>
</head>

<body class="body__auth">
    <?php
        require("./utils/connect.php");

        $password = $email = $username = $passerr = $emailerr = $usernameerr = null;
        if (isset($_POST['email'])) {

            $regexE = "/^[a-z0-9\.-_]+@[a-z0-9]+\.[a-z]{2,3}/";
            $email = htmlspecialchars($_POST["email"]);
            if (!preg_match($regexE, $email)) {
                $emailerr = "Invalid email";
            }

            $regexP = "/(?=.{8,})(?=.*[a-z])(?=.*[A-Z])/";
            $password = htmlspecialchars($_POST["password"]);
            if (!preg_match($regexP, $password)) {
                $passerr = "Invalid password";
            }

            $regexU = "/[a-z0-9]{6,}/";
            $username = htmlspecialchars($_POST["username"]);
            if (!preg_match($regexU, $username)) {
                $usernameerr = "Invalid username";
            }

            if (!$passerr && !$emailerr && !$usernameerr) {
                $hashedpassword = password_hash($password, PASSWORD_ARGON2I);
                $sql = "INSERT INTO Users(Email, Username, PasswordHash) VALUES ('$email','$username','$hashedpassword')";
                $sql2 = "SELECT UserStatus, Username, Email FROM users WHERE Username = '$username'";
                if (!$conn->query($sql)) {
                    if (str_contains($conn->error,"'Email_un'")) {
                        $emailerr = "Email already exists";
                    } elseif (str_contains($conn->error,"'Username_un'")) {
                        $usernameerr = "Username already exists";
                    } else {
                        print_r($conn->error);
                    }
                } elseif (!$result = $conn->query($sql2)) {
                  print_r($conn->error);
                } else {
                    $result = $result->fetch_assoc();
                    session_start();
                    $_SESSION['username'] = $result['Username'];
                    $_SESSION['role'] = $result['UserStatus'];
                    $_SESSION['email'] = $result['Email'];
                    header("Location: /rgz");
                }
            }
        }
    ?>
    <div class="form__container">
        <form id="form" class="auth" method="POST">
            <div class="heading">
                <div class="title">Car Gallery</div>
                <div>Sign Up</div>
            </div>
            <div class="form__inputs">
                <div class="inp">
                    <label for="email">Email</label>
                    <input name="email" placeholder="Email" value="<?=$email?>" />
                    <div class="error"><?=$emailerr?></div>
                </div>
                <div class="inp">
                    <label for="username">Username</label>
                    <input name="username" placeholder="Username" value="<?=$username?>" />
                    <div class="error"><?=$usernameerr?></div>
                </div>
                <div class="inp">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" value="<?=$password?>" />
                    <div class="error"><?=$passerr?></div>
                </div>
            </div>
            <div class="footer">
                <button type="submit">
                    Sign Up
                </button>
                <div class="link">
                    <a href="/rgz/login.php">Already have an account?</a>
                </div>
            </div>
        </form>
    </div>

    <div class="auth__copyright">
        &copy; pahanprog 2020-2021
    </div>

</body>

</html>