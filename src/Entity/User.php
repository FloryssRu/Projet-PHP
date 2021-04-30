<?php

namespace App\Entity;

class User
{

    private int $id;
    private string $pseudo;
    private string $password;
    private string $email;
    private bool $admin;

    public function getId(): int
    {
        $id = $this->id;
        return $id;
    }

    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    public function getPseudo(): string
    {
        $pseudo = $this->pseudo;
        return $pseudo;
    }

    public function setPassword(string $passwordHash): void
    {
        $this->password = $passwordHash;
    }

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

    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }

    public function getAdmin(): bool
    {
        $admin = $this->admin;
        return $admin;
    }

    public function __construct($pseudo, $password, $email, $admin)
    {
        $data = array($pseudo, $password, $email, $admin);
        $this->hydrate($data);
    }

    /**
     * Calls each set method for the attributes
     * 
     * @param array $data This is the description.
     */
    private function hydrate(array $data)
    {
        foreach ($data as $key => $value)
        {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }
}