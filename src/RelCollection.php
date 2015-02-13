<?php

namespace ArDev\HAL;

class RelCollection
{
    const HREF = 'href';

    /**
     * @var object | array
     */
    protected $rels;

    /**
     * @param object | array
     */
    public function __construct($rels = null)
    {
        $this->rels = (array) $rels;
    }

    /**
     * Get all rels
     *
     * @return array
     */
    public function all()
    {
        return $this->rels;
    }

    /**
     * Another way to access href of a named link
     *
     * @see    self::getHref
     * @param  string $key
     * @return string | null
     */
    public function __get($key)
    {
        return $this->getHref($this->dasheize($key));
    }

    /**
     * Get the href HAL key from a named link
     *
     * @param  string $key
     * @throws \LogicException If HAL format is not respected
     * @return string | null
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

    protected function dasheize($str)
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '-$1', $str));
    }
}
