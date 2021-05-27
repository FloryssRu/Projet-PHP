<?php

namespace App\Core\Exceptions;

class PropertyNotFoundException extends \Exception
{
    private $className;
	private $property;

    public function __construct($className, $property, $message = "Property missing")
    {
		$this->className = $className;
        $this->property = $property;
        parent::__construct($message,'0040');
    }

    public function getMoreDetail()
    {
        return "Property " . $this->property . " does not exist in class " . $this->className;
    }
}