<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Image\Metadata;

/**
 * An interface for Image Metadata
 */
class MetadataBag implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /** @var array */
    private $data;

    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * Returns the metadata key, default value if it does not exist
     *
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * Returns metadata as an array
     *
     * @return array An associative array
     */
    public function toArray()
    {
        return $this->data;
    }
}
