<?php
include "conn.php";

try {
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $msgError = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password != $confirm_password) {
            $msgError = "<p id='msgerror'>Password don't match !</p>";
            exit;
        }

        if (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/\d/', $password) ||
            !preg_match('/[^A-Za-z\d]/', $password)
        ) {
            $msgError = "<p id='msgerror'>Password is not strong enough!</p>";

            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM user WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $msgError = "<p id='msgerror'>This login " . $login . ' is already taken!</p>';
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO user (login, password) VALUES (:login, :password)");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindValue(':id_user', $_SESSION['id_user']);

        if ($stmt->execute()) {
            header("Location: connexion.php");
            exit;
        } else {
            $msgError = "<p id='msgerror'>Registration error</p>";
        }
    }
} catch(PDOException $e) {
    $msgError = "<p id='msgerror'>Error: " . $e->getMessage() . "</p>";
}

$conn = null;
?>

<!-- ---------------------------------------------------------------------------------------------------- -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>  
        <div class="top_nav">
            <ul>
            <?php if(!isset($_SESSION['login'])){ ?> 
                <li><a href="index.php">Home</a></li>
                <li><a href="inscription.php">Register</a></li>
                <li><a href="connexion.php">Login</a></li>
                <li><a href="livreor.php">Guestbook</a></li>

            <?php } else{?>
                <li><a href="index.php">Home</a></li>
                <li><a href="livreor.php">Guestbook</a></li>
                <li><a href="profil.php">Modify my Profil</a></li>
                <li><a href="deconnexion.php">Disconnect</a></li>

            <?php } ?>
            </ul>
        </div>
    <div class="form_box register">
            <h2>Registration</h2>
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
                    <div class="input_box">
                        <span class="icon"></span>
                        <input type="password" name="confirm_password" required>
                        <label>Confirm Password</label>
                    </div>
                    <div class="remember_forgot">
                        <label><input type="checkbox" required>
                        I agree to the terms & conditions</label>
                    </div>
                    <button type="submit" class="btn">Register</button>
                    <div class="login_register">
                        <p>Already have an account? <a href="connexion.php" class="login_link">Login</a></p>
                    </div>
                    <?php echo $msgError; ?>  
                </form>
            </div>
        </div>       
    </div>
</div>
</body>
</html>
