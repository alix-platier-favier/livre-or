<?php include 'conn.php';?>

<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: connexion.php");
    exit;
}

try {
    $msgError = "";
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $login = $_SESSION['login'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE login = :login");
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $hashedPassword = $user['password'];


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!password_verify($oldPassword, $hashedPassword)) {
            $msgError = "<p id='msgerror'>old password is incorrect !</p>";
            exit;
        }

        $login = $_POST['login'];

        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE user SET password = :password WHERE login = :login");
        $stmt->bindParam(':password', $hashedNewPassword);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        $_SESSION['login'] = $login;

        $msgError = "<p id='msgerror'>Profil Info updated with success !</p>";
    }
} catch(PDOException $e) {
    $msgError = "<p id='msgerror'>Error: " . $e->getMessage() . "</p>";
}

$conn = null;
?>

<!-- ----------------------------------------------------------------- -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="top_nav">
            <nav class="top_nav">

                <?php if(!isset($_SESSION['login'])){ ?> 
                    <a href="index.php">Home</a>
                    <a href="inscription.php">Register</a>
                    <a href="connexion.php">Login</a>
                    <a href="livreor.php">Guestbook</a>

                <?php } else{?>
                    <a href="profil.php">Modify my Profil</a>
                    <a href="livreor.php">Guestbook</a>
                    <a href="deconnexion.php">Disconnect</a>

                <?php } ?>
            </nav>
        </header>
    <div class="form_box profil">
            <h2>My Profil</h2>
                <form action="" method="POST">
                    <div class="input_box">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <input type="text" name="login" value="<?php echo $_SESSION['login']; ?>">
                        <label class="active">Username</label>
                    </div>
                    <div class="input_box">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="old_password" required>
                        <label class="active">Old Password</label>
                    </div>
                    <div class="input_box">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password">
                        <label class="active">New Password</label>
                    </div>
                    <div class="input_box">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="confirm_password">
                        <label class="active">Confirm New Password</label>
                    </div>
                    <button type="submit" class="btn">Update Info</button>
                    <?php echo $msgError; ?>  
                </form>
            </div>
        </div>       
    </div>
</div>

</body>
</html>
