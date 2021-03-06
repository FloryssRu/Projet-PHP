<?php

/**
 * File Post class
 *
 * Structure of a blog post
 *
 * @author  Floryss Rubechi <floryss.rubechi@gmail.com>
 *
 * @since 1.0
 */

namespace App\Entity;

class Post extends Entity
{
    private ?int $id;
    private string $title;
    private string $slug;
    private string $datePublication;
    private ?string $dateLastUpdate;
    private string $heading;
    private string $content;
    private string $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setDatePublication(string $datePublication): void
    {
        $this->datePublication = $datePublication;
    }

    public function getDatePublication(): string
    {
        return $this->datePublication;
    }

    /**
     * set the date of the last update of the post
     *
     * @param  string $dateLastUpdate The date of the last update (or NULL if the post isn't modified)
     * @return void
     */
    public function setDateLastUpdate(?string $dateLastUpdate): void
    {
        $this->dateLastUpdate = $dateLastUpdate;
    }

    public function getDateLastUpdate(): ?string
    {
        return $this->dateLastUpdate;
    }

    /**
     * set the heading of the post
     *
     * @param  string $heading The heading, the introduction of the post
     * @return void
     */
    public function setHeading(string $heading): void
    {
        $this->heading = $heading;
    }

    public function getHeading(): string
    {
        return $this->heading;
    }

    /**
     * set the content, the main part, the body of the post
     *
     * @param  string $content Is a long text with line breaks
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getAttributes(Object $object)
    {
        foreach ($object as $attribute => $value) {
            $array[$attribute] = $value;
        }
        return $array;
    }
}