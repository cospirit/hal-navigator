<?php

namespace CoSpirit\HAL;

class NavigatorCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * An array containing the entries of this collection.
     */
    protected array $elements;

    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * Create an Navigator object.
     *
     * @param array|object $element
     */
    protected function createNavigator($element): Navigator
    {
        return new Navigator($element);
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function first(): Navigator
    {
        return $this->createNavigator(reset($this->elements));
    }

    public function last(): Navigator
    {
        return $this->createNavigator(end($this->elements));
    }

    public function key(): string|int
    {
        return key($this->elements);
    }

    public function next(): Navigator
    {
        return $this->createNavigator(next($this->elements));
    }

    public function current(): Navigator
    {
        return $this->createNavigator(current($this->elements));
    }

    public function remove($key): mixed
    {
        if (isset($this->elements[$key]) || array_key_exists($key, $this->elements)) {
            $removed = $this->elements[$key];
            unset($this->elements[$key]);

            return $removed;
        }

        return null;
    }

    public function removeElement($element): bool
    {
        $key = array_search($element, $this->elements, true);

        if (false !== $key) {
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
    public function offsetExists($offset): bool
    {
        return $this->containsKey($offset);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetGet($offset): ?Navigator
    {
        return $this->get($offset);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        if (!isset($offset)) {
            $this->add($value);
        }

        $this->set($offset, $value);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }

    public function containsKey($key): bool
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    public function contains($element): bool
    {
        return in_array($element, $this->elements, true);
    }

    public function exists(\Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $this->createNavigator($element))) {
                return true;
            }
        }

        return false;
    }

    public function indexOf($element): string|int|bool
    {
        return array_search($element, $this->elements, true);
    }

    public function get($key): ?Navigator
    {
        if (isset($this->elements[$key])) {
            return $this->createNavigator($this->elements[$key]);
        }

        return null;
    }

    public function getKeys(): array
    {
        return array_keys($this->elements);
    }

    public function getValues(): array
    {
        return array_values($this->elements);
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function set($key, $value): void
    {
        $this->elements[$key] = $value;
    }

    public function add($value): bool
    {
        $this->elements[] = $value;

        return true;
    }

    public function isEmpty(): bool
    {
        return !$this->elements;
    }

    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritDoc}
     */
    public function getIterator(): NavigatorIterator
    {
        return new NavigatorIterator($this->elements);
    }

    public function map(\Closure $p): self
    {
        $c = function ($el) use ($p) {
            return $p($this->createNavigator($el));
        };

        return new static(array_map($c, $this->elements));
    }

    public function filter(\Closure $p): self
    {
        $c = function ($el) use ($p) {
            return $p($this->createNavigator($el));
        };

        return new static(array_filter($this->elements, $c));
    }

    public function forAll(\Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if (!$p($key, $this->createNavigator($element))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return static[]
     */
    public function partition(\Closure $p): array
    {
        $coll1 = $coll2 = [];
        foreach ($this->elements as $key => $element) {
            $this->createNavigator($element);

            if ($p($key, $element)) {
                $coll1[$key] = $element;
            } else {
                $coll2[$key] = $element;
            }
        }

        return [new static($coll1), new static($coll2)];
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__.'@'.spl_object_hash($this);
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function slice($offset, $length = null): self
    {
        return new static(array_slice($this->elements, $offset, $length, true));
    }
}
