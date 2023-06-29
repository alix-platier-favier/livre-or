<?php

include './User.php';

define("SERVERNAME", "localhost:3306");
define("USERNAME", "root");
define("PASSWORD", "159753");
define("DBNAME", "livreor");

class MyPdo
{
    private $connection;

    public function __construct()
    {
        $this->connection = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getUserByLogin($login)
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return new user($row['id'], $row['login'], $row['password']);
        } else {
            return null;
        }
    }

    public function getUserById($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return new user($row['id'], $row['login'], $row['password']);
        } else {
            return null;
        }
    }

    public function addUser($login, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("INSERT INTO user (login, password) VALUES (:login, :password)");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
    }

    public function updateUser($login, $newLogin, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("UPDATE user SET login = :newLogin, password = :password WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':newLogin', $newLogin);
        $stmt->execute();
    }

    public function getComments()
    {
        $query = $this->connection->query("SELECT comment.comment, comment.date, user.login FROM comment JOIN user ON comment.id_user=user.id ORDER BY date DESC");
        return $query->fetchAll();
    }

    public function createComment($content, $idUser, $date)
    {
        $stmt = $this->connection->prepare("INSERT INTO comment (comment, id_user, date) VALUES (?, ?, ?)");
        $stmt->execute([$content, $idUser, $date]);
    }
}
