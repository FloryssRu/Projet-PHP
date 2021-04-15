<?php

namespace App\Entity;

class Comment
{

    private int $id;
    private string $content;
    private string $date;
    private int $isValidated;

    public function getId(): int
    {
        $id = $this->id;
        return $id;
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

    public function setIsValidated(int $isValidated): void
    {
        $this->isValidated = $isValidated;
    }

    public function getIsValidated(): int
    {
        $isValidated = $this->isValidated;
        return $isValidated;
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