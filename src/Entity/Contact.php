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
        $id = $this->id;
        return $id;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getFirstName(): string
    {
        $firstName = $this->firstName;
        return $firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLastName(): string
    {
        $lastName = $this->lastName;
        return $lastName;
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

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        $title = $this->title;
        return $title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        $content = $this->content;
        return $content;
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