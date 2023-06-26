<?php

class User
{
    public function __construct(private int $id, private string $username, private string $hashedPwd)
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getHashedPwd()
    {
        return $this->hashedPwd;
    }
}
