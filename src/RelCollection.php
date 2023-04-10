<?php

namespace CoSpirit\HAL;

class RelCollection
{
    public const HREF = 'href';

    /**
     * @var object|array
     */
    protected $rels;

    /**
     * @param object|array $rels
     */
    public function __construct($rels = null)
    {
        $this->rels = (array) $rels;
    }

    /**
     * Get all rels.
     */
    public function all(): array
    {
        return $this->rels;
    }

    /**
     * Another way to access href of a named link.
     *
     * @see    self::getHref
     *
     * @param string $key
     *
     * @return string|null
     */
    public function __get($key)
    {
        return $this->getHref($this->dasheize($key));
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return true;
    }

    /**
     * Get the href HAL key from a named link.
     *
     * @param string $key
     *
     * @throws \LogicException If HAL format is not respected
     *
     * @return string|null
     */
    public function getHref($key)
    {
        if (isset($this->rels[$key])) {
            if (!isset($this->rels[$key][static::HREF])) {
                throw new \LogicException(sprintf('HAL malformed, href not found on "%s" link', $key));
            }

            return $this->rels[$key][static::HREF];
        }

        return null;
    }

    protected function dasheize($str): string
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '-$1', $str));
    }
}
