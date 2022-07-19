<?php

namespace Midnite81\Xml2Array;

use ArrayAccess;
use ArrayIterator;
use Exception;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Midnite81\Xml2Array\Exceptions\CollectionsNotFoundException;
use ReturnTypeWillChange;
use Traversable;

class XmlResponse implements IteratorAggregate, ArrayAccess
{
    protected array $array;

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
     * @return array
     */
    public function toArray(): array
    {
        return $this->array;
    }

    /**
     * Serialise the array
     *
     * @return string
     */
    public function serialize(): string
    {
        return serialize($this->array);
    }

    /**
     * Alias of serialise
     *
     * @return string
     */
    public function serialise(): string
    {
        return $this->serialize();
    }

    /**
     * To Json
     *
     * @return false|string
     */
    public function toJson(): bool|string
    {
        return json_encode($this->array);
    }

    /**
     * To Laravel Collection (if installed)
     *
     * @return Collection
     * @throws Exception
     */
    public function toCollection(): Collection
    {
        if (function_exists('collect')) {
            return collect($this->array);
        }
        throw new CollectionsNotFoundException('Laravel Collections do not appear to be installed');
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable|ArrayIterator
     */
    public function getIterator(): Traversable|ArrayIterator
    {
        return new ArrayIterator($this->array);
}

    /**
     * Whether a offset exists
     *
     * @inheritdoc
     */
    public function offsetExists($offset): bool
    {
        return isset($this->array[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @inheritdoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->array[$offset] ?? null;
    }

    /**
     * Offset to set
     *
     * @inheritdoc
     */
    #[ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        return $this->array[$offset] ?? null;
    }

    /**
     * Offset to unset
     *
     * @inheritdoc
     */
    public function offsetUnset($offset): void
    {
        unset($this->array[$offset]);
    }
}