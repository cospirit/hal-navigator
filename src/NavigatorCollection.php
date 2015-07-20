<?php

namespace ArDev\HAL;

class NavigatorCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * An array containing the entries of this collection.
     *
     * @var array
     */
    protected $elements;

    /**
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        $this->elements = $elements;
    }

    /**
     * Create an Navigator object
     *
     * @param  array | object $element
     * @return Navigator
     */
    protected function createNavigator($element)
    {
        return new Navigator($element);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * {@inheritDoc}
     */
    public function first()
    {
        return $this->createNavigator(reset($this->elements));
    }

    /**
     * {@inheritDoc}
     */
    public function last()
    {
        return $this->createNavigator(end($this->elements));
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return $this->createNavigator(next($this->elements));
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->createNavigator(current($this->elements));
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        if (isset($this->elements[$key]) || array_key_exists($key, $this->elements)) {
            $removed = $this->elements[$key];
            unset($this->elements[$key]);

            return $removed;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function removeElement($element)
    {
        $key = array_search($element, $this->elements, true);

        if ($key !== false) {
            unset($this->elements[$key]);

            return true;
        }

        return false;
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            return $this->add($value);
        }
        return $this->set($offset, $value);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function containsKey($key)
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function contains($element)
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * {@inheritDoc}
     */
    public function exists(\Closure $p)
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $this->createNavigator($element))) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        if (isset($this->elements[$key])) {
            return $this->createNavigator($this->elements[$key]);
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getKeys()
    {
        return array_keys($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function getValues()
    {
        return array_values($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $this->elements[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function add($value)
    {
        $this->elements[] = $value;
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return ! $this->elements;
    }

    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new NavigatorIterator($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function map(\Closure $p)
    {
        $c = function ($el) use ($p) {
            return $p($this->createNavigator($el));
        };

        return new static(array_map($c, $this->elements));
    }

    /**
     * {@inheritDoc}
     */
    public function filter(\Closure $p)
    {
        $c = function ($el) use ($p) {
            return $p($this->createNavigator($el));
        };

        return new static(array_filter($this->elements, $c));
    }

    /**
     * {@inheritDoc}
     */
    public function forAll(\Closure $p)
    {
        foreach ($this->elements as $key => $element) {
            if (!$p($key, $this->createNavigator($element))) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function partition(\Closure $p)
    {
        $coll1 = $coll2 = array();
        foreach ($this->elements as $key => $element) {
            $this->createNavigator($element);

            if ($p($key, $element)) {
                $coll1[$key] = $element;
            } else {
                $coll2[$key] = $element;
            }
        }
        return array(new static($coll1), new static($coll2));
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . '@' . spl_object_hash($this);
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->elements = array();
    }

    /**
     * {@inheritDoc}
     */
    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->elements, $offset, $length, true));
    }
}
