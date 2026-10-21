<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Gd;

use Imagine\Image\AbstractLayers;
use Imagine\Exception\RuntimeException;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Image\Palette\PaletteInterface;
use Imagine\Exception\NotSupportedException;

class Layers extends AbstractLayers
{
    private $image;
    private $offset;
    private $resource;
    private $palette;

    public function __construct(Image $image, PaletteInterface $palette, $resource)
    {
        if (!is_resource($resource)) {
            throw new RuntimeException('Invalid Gd resource provided');
        }

        $this->image = $image;
        $this->resource = $resource;
        $this->offset = 0;
        $this->palette = $palette;
    }

    /**
     * {@inheritdoc}
     */
    public function merge()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function coalesce()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function animate($format, $delay, $loops)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function current(): mixed
    {
        return new Image($this->resource, $this->palette, new MetadataBag());
    }

    /**
     * {@inheritdoc}
     */
    public function key(): mixed
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        ++$this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->offset = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->offset < 1;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return 0 === $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        if (0 === $offset) {
            return new Image($this->resource, $this->palette, new MetadataBag());
        }

        throw new RuntimeException('GD only supports one layer at offset 0');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        throw new NotSupportedException('GD does not support layer set');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        throw new NotSupportedException('GD does not support layer unset');
    }
}
