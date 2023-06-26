<?php

include './MyPdo.php';

class App
{
    private $pdo;
    public $msgError;

    public function __construct()
    {
        try {
            $this->pdo = new MyPdo;
        } catch (PDOException $e) {
            $this->msgError = "<p id='msgerror'>Erreur : " . $e->getMessage() . "</p>";
            exit;
        }
    }

    public function login($login, $password)
    {
        try {
            $user = $this->pdo->getUserByLogin($login);
        } catch (PDOException $e) {
            $this->msgError = "<p id='msgerror'>Erreur : " . $e->getMessage() . "</p>";
            return false;
        }
        if ($user === null) {
            $this->msgError = "<p id='msgerror'>Incorrect Username!</p>";
            return false;
        }
        if (password_verify($password, $user->getHashedPwd())) {
            $_SESSION['loggedin'] = true;
            $_SESSION['id_user'] = $user->getId();
            $_SESSION['login'] = $user->getUsername();
            return true;
        } else {
            $this->msgError = "<p id='msgerror'>Incorrect Password!</p>";
            return false;
        }
    }

    public function subscribe($login, $password, $confirmPassword)
    {
        if ($password != $confirmPassword) {
            $this->msgError = "<p id='msgerror'>Passwords don't match !</p>";
            return false;
        }
        if (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/\d/', $password) ||
            !preg_match('/[^A-Za-z\d]/', $password)
        ) {
            $this->msgError = "<p id='msgerror'>Password is not strong enough!</p>";
            return false;
        }
        try {
            if ($this->pdo->getUserByLogin($login) != null) {
                $this->msgError = "<p id='msgerror'>This login " . $login . ' is already taken!</p>';
                return false;
            }
            $this->pdo->addUser($login, $password);
        } catch (PDOException $e) {
            $this->msgError = "<p id='msgerror'>Erreur : " . $e->getMessage() . "</p>";
            return false;
        }

        return true;
    }

    public function updateProfile($newLogin, $oldPwd, $newPwd, $confirmPassword)
    {
        try {
            $user = $this->pdo->getUserByLogin($_SESSION['login']);

            if (!password_verify($oldPwd, $user->getHashedPwd())) {
                $this->msgError = "<p id='msgerror'>old password is incorrect !</p>";
                return false;
            }
            if ($newLogin != $_SESSION['login'] && $this->pdo->getUserByLogin($newLogin) != null) {
                $this->msgError = "<p id='msgerror'>This login " . $newLogin . ' is already taken!</p>';
                return false;
            }
            if ($newPwd != $confirmPassword) {
                $this->msgError = "<p id='msgerror'>Passwords don't match !</p>";
                return false;
            }
            if (
                !empty($newPwd) && (strlen($newPwd) < 8 ||
                    !preg_match('/[A-Z]/', $newPwd) ||
                    !preg_match('/[a-z]/', $newPwd) ||
                    !preg_match('/\d/', $newPwd) ||
                    !preg_match('/[^A-Za-z\d]/', $newPwd)
                )
            ) {
                $this->msgError = "<p id='msgerror'>Password is not strong enough!</p>";
                return false;
            }
        } catch (PDOException $e) {
            $this->msgError = "<p id='msgerror'>Error: " . $e->getMessage() . "</p>";
        }
        $_SESSION['login'] = $newLogin;
        return true;
    }

    public function addComment($content, $idUser)
    {
        if (empty($content) && strlen($content) < 10) {
            $this->msgError = "<p id='msgerror'>Your message must contain at least 10 characters.</p>";
            return false;
        }
        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d H:i:s');
        try {
            $this->pdo->createComment($content, $idUser, $date);
        } catch (PDOException $e) {
            $this->msgError = "<p id='msgerror'>Error: " . $e->getMessage() . "</p>";
            return false;
        }
        return true;
    }

    public function getComments()
    {
        try {
            $comments = $this->pdo->getComments();
            return $comments;
        } catch (PDOException $e) {
            $this->msgError = "<p id='msgerror'>Error: " . $e->getMessage() . "</p>";
            return false;
        }
    }
}
