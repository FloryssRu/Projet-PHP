<?php

namespace App\Core\Response;

interface RedirectionInterface
{
    public function redirect(string $path);
}