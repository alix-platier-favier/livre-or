<?php

include './App.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $app = new App;
    if ($app->login($login, $password)) {
        if ($_SESSION['login'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: livreor.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="top_nav">
        <ul>
            <?php if (!isset($_SESSION['login'])) { ?>
                <li><a href="index.php">Home</a></li>
                <li><a href="inscription.php">Register</a></li>
                <li><a href="connexion.php">Login</a></li>
                <li><a href="livreor.php">Guestbook</a></li>

            <?php } else { ?>
                <li><a href="index.php">Home</a></li>
                <li><a href="livreor.php">Guestbook</a></li>
                <li><a href="profil.php">Modify my Profil</a></li>
                <li><a href="deconnexion.php">Disconnect</a></li>

            <?php } ?>
        </ul>
    </div>
    <div class="form_box login">
        <h2>Login</h2>
        <form action="" method="POST">
            <div class="input_box">
                <span class="icon"></span>
                <input type="text" name="login" required>
                <label>Username</label>
            </div>
            <div class="input_box">
                <span class="icon"></span>
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <div class="remember_forgot">
                <label><input type="checkbox">
                    Remember me</label>
                <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="login_register">
                <p>Don't have an account? <a href="inscription.php" class="register_link">Register</a></p>
            </div>
            <?php if (isset($app->msgError)) {
                echo $app->msgError;
            } ?>
        </form>
    </div>
</body>

</html>