<?php

namespace CoSpirit\HAL;

class NavigatorIterator implements \Iterator
{
    /**
     * @var int
     */
    protected $position;

    /**
     * @var array
     */
    protected $elements;

    /**
     * Constructor.
     */
    public function __construct(array $elements)
    {
        $this->elements = $elements;
        $this->position = 0;
    }

    /**
     * Create a new Navigator object from an element
     *
     * @param  int $position
     * @return Navigator
     */
    protected function get($position)
    {
        return new Navigator($this->elements[$position]);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->get($this->position);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->elements[$this->position]);
    }
}
