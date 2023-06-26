<?php include './App.php';

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: connexion.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newLogin = $_POST['login'];
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $app = new App;
    $app->updateProfile($newLogin, $oldPassword, $newPassword, $confirmPassword);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
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
    <div class="form_box profil">
        <h2>My Profil</h2>
        <form action="" method="POST">
            <div class="input_box">
                <span class="icon"></span>
                <input type="text" name="login" value="<?php echo $_SESSION['login']; ?>">
                <label class="active">Username</label>
            </div>
            <div class="input_box">
                <span class="icon"></span>
                <input type="password" name="old_password" required>
                <label class="active">Old Password</label>
            </div>
            <div class="input_box">
                <span class="icon"></span>
                <input type="password" name="password">
                <label class="active">New Password</label>
            </div>
            <div class="input_box">
                <span class="icon"></span>
                <input type="password" name="confirm_password">
                <label class="active">Confirm New Password</label>
            </div>
            <button type="submit" class="btn">Update Info</button>
            <?php if (isset($app->msgError)) {
                echo $app->msgError;
            } ?>
        </form>
    </div>
    </div>
    </div>
    </div>

</body>

</html>