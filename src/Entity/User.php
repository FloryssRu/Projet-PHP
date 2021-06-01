<?php

namespace App\Entity;

/**
 * File User class
 *
 * Structure of an user informations
 *
 * @author  Floryss Rubechi <floryss.rubechi@gmail.com>
 *
 * @since 1.0
 */

class User
{

    private int $id;
    private string $pseudo;
    private string $password;
    private string $email;
    private bool $admin;
    private bool $emailValidated;
    private ?string $uuid;

    public function getId(): int
    {
        return $this->id;
    }

    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPassword(string $passwordHash): void
    {
        $this->password = $passwordHash;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }

    public function getAdmin(): bool
    {
        return $this->admin;
    }

    public function setEmailValidated(bool $emailValidated): void
    {
        $this->emailValidated = $emailValidated;
    }

    public function getEmailValidated(): bool
    {
        return $this->emailValidated;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function __construct($pseudo, $password, $email, $admin, $emailValidated, $uuid)
    {
        $data = [
            'pseudo' => $pseudo,
            'password' => $password,
            'email' => $email,
            'admin' => $admin,
            'emailValidated' => $emailValidated,
            'uuid' => $uuid
        ];
        $this->hydrate($data);
    }

    /**
     * Calls each set method for the attributes
     * 
     * @param array $data All the attributs to hydrate
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

    public function getAttributes(object $object)
    {
        foreach($object as $attribute => $value)
        {
            $array[$attribute] = $value;
        }
        return $array;
    }
}