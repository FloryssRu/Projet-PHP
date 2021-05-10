<?php

namespace App\Entity;

class Comment
{

    private int $id;
    private string $pseudo;
    private string $content;
    private string $date;
    private bool $isValidated;

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
        return $this->pseudo;
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

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getDate(): string
    {
        $date = $this->date;
        return $date;
    }

    public function setIsValidated(bool $isValidated): void
    {
        $this->isValidated = $isValidated;
    }

    public function getIsValidated(): bool
    {
        $isValidated = $this->isValidated;
        return $isValidated;
    }

    public function __construct($pseudo, $content, $date, $isValidated)
    {
        $data = array($pseudo, $content, $date, $isValidated);
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