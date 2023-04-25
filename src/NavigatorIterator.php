<?php

namespace CoSpirit\HAL;

class NavigatorIterator implements \Iterator
{
    protected int $position;

    /**
     * @param mixed[] $elements
     */
    public function __construct(protected array $elements)
    {
        $this->position = 0;
    }

    /**
     * Create a new Navigator object from an element.
     */
    protected function get(int $position): Navigator
    {
        return new Navigator($this->elements[$position]);
    }

    /**
     * {@inheritdoc}
     */
    public function current(): Navigator
    {
        return $this->get($this->position);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return isset($this->elements[$this->position]);
    }
}
