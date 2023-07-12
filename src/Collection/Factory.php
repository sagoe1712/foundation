<?php

namespace sagoe1712\Foundation\Collection;

/**
 * Class Factory
 * @package sagoe1712\Foundation\Collection
 */
abstract class Factory
{
    /**
     * @return Collection A new collection instance.
     */
    public function getCollection()
    {
        return new Collection;
    }
}
