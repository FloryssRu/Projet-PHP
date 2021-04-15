<?php

namespace App\Entity;

class User
{

    private int $id;
    private string $pseudo;
    private string $password;
    private string $email;
    private int $admin;

    public function getId(): int
    {
        $id = $this->id;
        return $id;
    }

    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }
    //est-ce que je dois vérifier ou le hacher de nouveau ?

    public function getPseudo(): string
    {
        $pseudo = $this->pseudo;
        return $pseudo;
    }

    public function setPassword(string $passwordHash): void
    {
        $this->password = $passwordHash;
    }
    //dois-je préciser que le password est haché ou pas ?

    public function getPassword(): string
    {
        $password = $this->password;
        return $password;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        $email = $this->email;
        return $email;
    }

    public function setAdmin(int $admin): void
    {
        $this->admin = $admin;
    }

    public function getAdmin(): int
    {
        $admin = $this->admin;
        return $admin;
    }

    public function __construct()
    {
        //je dois appeller toutes les fonctions set() ?
    }

    private function hydrate()
    {
        //hydrate
    }
}