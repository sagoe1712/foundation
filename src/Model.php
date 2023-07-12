<?php

namespace sagoe1712\Foundation;

/**
 * Class Model
 * @package sagoe1712\Foundation
 */
abstract class Model
{
    /**
     * Parent constructor.
     */
    public function __construct()
    {
    }


    /**
     * Get all object properties as an array.
     */
    public function getAllProperties(): array
    {
        return get_object_vars($this);
    }


    /**
     * Return object (public/protected) properties as a JSON string.
     * @return string
     */
    public function __toString()
    {
        $properties = $this->getAllProperties();

        foreach ($properties as &$property) {
            if (is_object($property)) {
                $property = (array) $property;
            } else if (is_string($property)) {
                $property = mb_convert_encoding($property, "UTF-8", "UTF-8");
            }
        }

        $jsonString = json_encode($properties, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        return $jsonString !== false ? $jsonString : '{}';
    }
}
