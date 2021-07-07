<?php

namespace App\Entity;

class Entity
{
    /**
     * Calls each set method for the attributes
     * 
     * @param array $data All the attributes to hydrate
     */
    public function hydrate(Object $object, array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($object, $method)) {
                $object->$method($value);
            }
        }
    }
}