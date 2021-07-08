<?php

/**
 * File Contact class
 *
 * Structure of a contact form
 *
 * @author  Floryss Rubechi <floryss.rubechi@gmail.com>
 *
 * @since 1.0
 */

namespace App\Entity;

class Contact extends Entity
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $title;
    private string $content;
    private bool $isProcessed;

    public function getId(): int
    {
        return $this->id;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setIsProcessed(bool $isProcessed): void
    {
        $this->isProcessed = $isProcessed;
    }

    public function getIsProcessed(): bool
    {
        return $this->isProcessed;
    }

    public function getAttributes(Object $object)
    {
        foreach ($object as $attribute => $value) {
            $array[$attribute] = $value;
        }
        return $array;
    }

}