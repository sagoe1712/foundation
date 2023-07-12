<?php

namespace sagoe1712\Foundation\Collection;

/**
 * Interface CollectionInterface
 * @package sagoe1712\Foundation\Collection
 */
interface CollectionInterface extends \Countable, \ArrayAccess, \IteratorAggregate
{
    public function add($value, $key);


    public function remove($value);


    public function get($key);


    public function exists($key);
}
