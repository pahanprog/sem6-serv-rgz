<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./static/styles.css">
    <?php
    include('./utils/livereload.php');
    ?>
</head>

<body class="body__auth">
    <?php
        require("./utils/connect.php");

        $password = $text = $passerr = $texterr = $msg = null;
        if (isset($_POST['text'])) {
            $flag =  true;

            $regexE = "/^[a-z0-9\.-_]+@[a-z0-9]+\.[a-z]{2,3}/";
            $text = htmlspecialchars($_POST["text"]);
            if (!preg_match($regexE, $text)) {
                $flag = false;
            }

            $regexU = "/[a-z0-9]{6,}/";
            if (!preg_match($regexU, $text)) {
                if (!$flag) { 
                    $texterr = "Invalid username or email";
                }
            }

            $regexP = "/(?=.{8,})(?=.*[a-z])(?=.*[A-Z])/";
            $password = htmlspecialchars($_POST["password"]);
            if (!preg_match($regexP, $password)) {
                $passerr = "Invalid password";
            }

            if (!$passerr && !$texterr) {
                $sql = "SELECT Users.Username, Users.PasswordHash, UserRole.Id as role, Users.Email FROM Users JOIN UserRole ON (Users.UserStatus = UserRole.id) WHERE (Users.Username = '$text' OR Users.Email = '$text')";
                if (!$result = $conn->query($sql)) {
                    print_r($conn->error);
                } else{
                    $result = $result->fetch_assoc();
                    if (isset($result['PasswordHash'])) {
                        if (password_verify($password, $result['PasswordHash'])) {
                            session_start();
                            $_SESSION['username'] =$result['Username'];
                            $_SESSION['role'] = $result['role'];
                            $_SESSION['email'] = $result['Email'];
                            header('Location: /rgz');
                        } else {
                            $passerr = "Incorrect password";
                        }
                    } else {
                        $texterr = "user not found";
                    }
                }
            }
        }
    ?>

    <div class="form__container">
        <form id="form" class="auth" method="POST">
            <div class="heading">
                <div class="title">Car Gallery</div>
                <div>Sign In</div>
            </div>
            <div class="form__inputs">
                <div class="inp">
                    <label for="text">Email or username</label>
                    <input name="text" placeholder="Email or usesrname" value="<?=$text?>" autocomplete="email" />
                    <div class="error"><?=$texterr?></div>
                </div>
                <div class="inp">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" value="<?=$password?>" />
                    <div class="error"><?=$passerr?></div>
                </div>
                <div class="footer">
                    <button type="submit">Sign In</button>
                    <div class="link">
                        <a href="/rgz/register.php">Dont have an account?</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="auth__copyright">
        &copy; pahanprog 2020-2021
    </div>

</body>

</html>