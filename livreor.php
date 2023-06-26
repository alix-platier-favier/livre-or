<?php
include './App.php';

session_start();

$app = new App;
$comments = $app->getComments();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guestbook</title>
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
    <section class="form_box" id="table_container">

        <?php if (isset($_SESSION['login'])) { ?>
            <a href="commentaire.php" id="mybutton"><button id="mybutton">Leave a comment</button></a>
        <?php }; ?>

        <div class="form_box" id="comment_container">
            <?php
            foreach ($comments as $comment) {
                $user = $comment['login'];
                $content = $comment['comment'];
                $date = date('d-m-Y H:i', strtotime($comment['date']));
                echo '<p id="post">Posted on the ' . $date .
                    ' by ' . $user . '</p><br>' .
                    '<p id="comment">' . $comment['comment'] .
                    '</p><br><br>';
            }
            ?>
        </div>
    </section>
    </main>
</body>

</html>