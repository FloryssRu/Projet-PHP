<?php

namespace App\Entity;

/**
 * File Post class
 *
 * Manages the blog posts
 *
 * @author  Floryss Rubechi <floryss.rubechi@gmail.com>
 *
 * @since 1.0
 *
 * @param int    $example  This is an example function/method parameter description.
 * @param string $example2 This is a second example.
 */

class Post
{
    
    private int $id;
    private string $title;
    private string $datePublication;
    private string $dateLastUpdate;
    private string $heading;
    private string $content;
    

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

    
    /**
     * __construct
     *
     * @param  string $title
     * @param  string $datePublication
     * @param  string $dateLastUpdate
     * @param  string $heading
     * @param  string $content
     * @return void
     */
    public function __construct($title, $datePublication, $dateLastUpdate, $heading, $content)
    {
        $data = array($title, $datePublication, $dateLastUpdate, $heading, $content);
        $this->hydrate($data);
    }

    /**
     * Calls each set method for the attributes
     * 
     * @param array $data This is the description.
     */
    public function hydrate(array $data)
    {
        foreach ($data as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }

}