<?php

/**
 * File User class
 *
 * Structure of an user informations
 *
 * @author  Floryss Rubechi <floryss.rubechi@gmail.com>
 *
 * @since 1.0
 */

namespace App\Entity;

class User extends Entity
{
    private ?int $id;
    private string $pseudo;
    private string $password;
    private string $email;
    private bool $admin;
    private bool $emailValidated;
    private int $avatarNumber;
    private ?string $uuid;

    public function getId(): ?int
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

    public function setAvatarNumber(int $avatarNumber): void
    {
        $this->avatarNumber = $avatarNumber;
    }

    public function getAvatarNumber(): int
    {
        return $this->avatarNumber;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getAttributes(Object $object)
    {
        foreach ($object as $attribute => $value) {
            $array[$attribute] = $value;
        }
        return $array;
    }
}