<?php

namespace sagoe1712\Foundation\Collection;

/**
 * Class Collection
 * @package sagoe1712\Foundation\Collection
 */
class Collection implements CollectionInterface
{
    /**
     * @var array $data An array of data stored in the collection.
     */
    protected $data = [];


    /**
     * Add data to the collection.
     *
     * @param mixed $value The data to add.
     * @param mixed $key The key to store the data against.
     * @throws \InvalidArgumentException
     */
    public function add($value, $key = null)
    {
        if ($key == null) {
            $this->data[] = $value;
        } else {
            if (isset($this->data[$key])) {
                throw new \InvalidArgumentException("Key $key already in use.");
            } else {
                $this->data[$key] = $value;
            }
        }
    }


    /**
     * Remove an item by its key.
     *
     * @param $key
     * @throws \InvalidArgumentException
     */
    public function remove($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        } else {
            throw new \InvalidArgumentException("Invalid key $key.");
        }
    }


    /**
     * Get a value by its key.
     *
     * @param $key
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            throw new \InvalidArgumentException("Invalid key $key.");
        }
    }


    /**
     * Check if an item exists by its key.
     *
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        return isset($this->data[$key]);
    }


    /**
     * Return a count of data elements.
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }


    /**
     * @return \ArrayIterator An ArrayIterator object.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }


    /**
     * @param mixed $key
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    public function offsetSet($key, $value)
    {
        if (!isset($key)) {
            $this->data[] = $value;
        } else {
            $this->data[$key] = $value;
        }
    }


    /**
     * @param mixed $key
     * @throws \InvalidArgumentException
     */
    public function offsetUnset($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        } else {
            throw new \InvalidArgumentException('The specified key does not exist.');
        }
    }


    /**
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->data[$key];
    }


    /**
     * @param mixed $key
     * @return bool|mixed
     */
    public function offsetExists($key)
    {
        return isset($this->data[$key]);
    }


    /**
     * When a collection is converted to a string it converts the data to a json string.
     */
    public function __toString()
    {
        $results = [];

        foreach ($this->data as $item) {
            array_push($results, $item->getAllProperties());
        }

        return json_encode($results);
    }
}
