<?php

include './App.php';

session_start();

if (!isset($_SESSION['login'])) {
    header('location: index.php');
}

if ($_POST) {

    if (!empty($comment) && strlen($comment) > 10) {
        $app = new App;
        $comment = htmlspecialchars($_POST['comment']);
        $idUser = $_SESSION['id_user'];
        if ($app->addComment($comment, $idUser)) {
            header("location: livreor.php");
        }
    } else {
        $msgError = "<p id='msgerror'>Your message must contain at least 10 characters.</p>";
    }
}

$app = new App;
$comments = $app->getComments();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave a comment</title>
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
    <div class="container">
        <form action="" Method="POST" class="flex-column">

            <label for="comment">Comments</label>
            <textarea name="comment" id="comment" cols="30" rows="10" placeholder="Write your message..."></textarea>

            <input type="submit" id="mybutton" value="Send">
            <?php echo $app->msgError; ?>

        </form>
    </div>
</body>

</html>