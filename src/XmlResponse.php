<?php

namespace Midnite81\Xml2Array;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Midnite81\Xml2Array\CollectionsNotFoundException;
use RecursiveIterator;
use Traversable;

class XmlResponse implements IteratorAggregate, ArrayAccess
{
    protected $array;

    /**
     * XmlResponse constructor.
     *
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * To array
     *
     * @return mixed
     */
    public function toArray()
    {
        return $this->array;
    }

    /**
     * Serialise the array
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->array);
    }

    /**
     * Alias of serialise
     *
     * @return string
     */
    public function serialise()
    {
        return $this->serialize();
    }

    /**
     * To Json
     *
     * @return mixed
     */
    public function toJson()
    {
        return json_encode($this->array);
    }

    /**
     * To Laravel Collection (if installed)
     *
     * @return mixed
     * @throws \Exception
     */
    public function toCollection()
    {
        if (function_exists('collect')) {
            return collect($this->array);
        }
        throw new CollectionsNotFoundException('Laravel Collections do not appear to be installed');
    }

    /**
     * To string
     *
     * @return mixed
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->array);
}

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     * @return boolean true on success or false on failure.
     *                      </p>
     *                      <p>
     *                      The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     * @codeCoverageIgnore
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     * @codeCoverageIgnore
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }
}