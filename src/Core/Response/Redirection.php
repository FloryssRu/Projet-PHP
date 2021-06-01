<?php

namespace App\Core\Response;

class Redirection implements RedirectionInterface
{
    private $path;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function redirect(string $path)
    {
        header("Location:/blogphp" . $path);
		exit();
    }

}