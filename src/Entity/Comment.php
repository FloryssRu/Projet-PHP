<?php

namespace App\Entity;

/**
 * File Comment class
 *
 * Structure of a comment
 *
 * @author  Floryss Rubechi <floryss.rubechi@gmail.com>
 *
 * @since 1.0
 */

class Comment extends Entity
{

    private int $id;
    private string $pseudo;
    private string $content;
    private string $date;
    private bool $isValidated;
    private int $idPost;

    public function __construct()
    {
        if(isset($this->is_validated))
        {
            $this->isValidated = $this->is_validated;
            unset($this->is_validated);
        }
        if(isset($this->id_post))
        {
            $this->idPost = $this->id_post;
            unset($this->id_post);
        }
    }

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

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setIsValidated(bool $isValidated): void
    {
        $this->isValidated = $isValidated;
    }

    public function getIsValidated(): bool
    {
        return $this->isValidated;
    }

    public function setIdPost(int $idPost): void
    {
        $this->idPost = $idPost;
    }

    public function getIdPost(): int
    {
        return $this->idPost;
    }
}