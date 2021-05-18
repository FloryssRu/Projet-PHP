<?php

namespace App\Entity;

/**
 * File Contact class
 *
 * Structure of a contact form
 *
 * @author  Floryss Rubechi <floryss.rubechi@gmail.com>
 *
 * @since 1.0
 */

class Contact
{

    private int $id;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $title;
    private string $content;

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

    public function __construct($firstName, $lastName, $email, $title, $content)
    {
        $data = array($firstName, $lastName, $email, $title, $content);
        $this->hydrate($data);
    }

    /**
     * Calls each set method for the attributes
     * 
     * @param array $data All the attributes to hydrate
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