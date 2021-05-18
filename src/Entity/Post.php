<?php

namespace App\Entity;

/**
 * File Post class
 *
 * Structure of a blog post
 *
 * @author  Floryss Rubechi <floryss.rubechi@gmail.com>
 *
 * @since 1.0
 */

class Post
{
    
    private int $id;
    private string $title;
    private string $datePublication;
    private string $dateLastUpdate;
    private string $heading;
    private string $content;
    private string $author;
    

    public function getId(): int
    {
        $id = $this->id;
        return $id;
    }
    
    /**
     * set title
     *
     * @param  string $title The title which we want to give
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        $title = $this->title;
        return $title;
    }

    /**
     * set the date of publication
     *
     * @param  string $datePublication The date the post was made
     * @return void
     */
    public function setDatePublication(string $datePublication): void
    {
        $this->datePublication = $datePublication;
    }

    public function getDatePublication(): string
    {
        $datePublication = $this->datePublication;
        return $datePublication;
    }

    /**
     * set the date of the last update of the post
     *
     * @param  string $dateLastUpdate The date of the last update
     * @return void
     */
    public function setDateLastUpdate(string $dateLastUpdate): void
    {
        $this->dateLastUpdate = $dateLastUpdate;
    }

    public function getDateLastUpdate(): string
    {
        $dateLastUpdate = $this->dateLastUpdate;
        return $dateLastUpdate;
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
        $heading = $this->heading;
        return $heading;
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
        $content = $this->content;
        return $content;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getAuthor(): string
    {
        $author = $this->author;
        return $author;
    }

    public function __construct($title, $datePublication, $dateLastUpdate, $heading, $content, $author)
    {
        $data = array($title, $datePublication, $dateLastUpdate, $heading, $content, $author);
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