<?php

namespace CoSpirit\HAL;

class Navigator
{
    public const RELS = '_links';
    public const EMBEDDED = '_embedded';

    /**
     * @var object|array
     */
    protected $content;

    /**
     * @var RelCollection
     */
    protected $rels;

    /**
     * @param object|array $content
     */
    public function __construct($content = null)
    {
        $this->content = (array) $content;
    }

    /**
     * Magic accessor.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->content)) {
            return $this->content[$key];
        } elseif ($this->isEmbeddedExists($key)) {
            return $this->getEmbedded($key);
        }

        if ('rels' == $key) {
            return $this->getRels();
        }
    }

    /**
     * Magic isser.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return true;
    }

    /**
     * Get rels links.
     *
     * @return RelCollection
     */
    public function getRels()
    {
        if (is_null($this->rels)) {
            if (!array_key_exists(static::RELS, $this->content)) {
                $this->rels = new RelCollection([]);
            } else {
                $this->rels = new RelCollection((array) $this->content[static::RELS]);
            }
        }

        return $this->rels;
    }

    /**
     * Check if an embedded exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function isEmbeddedExists($key)
    {
        if (
            array_key_exists(static::EMBEDDED, $this->content)
            && array_key_exists($key, $this->content[static::EMBEDDED])
        ) {
            return true;
        }

        return false;
    }

    /**
     * Get an embedded.
     *
     * @param string $key
     *
     * @return Navigator | NavigatorCollection[Navigator]
     */
    public function getEmbedded($key)
    {
        if (!$this->isEmbeddedExists($key)) {
            return new NavigatorCollection();
        }

        $embedded = $this->content[static::EMBEDDED][$key];

        if (is_array($embedded)) {
            if (!empty($embedded)) {
                if (is_int(array_keys($embedded)[0])) {
                    return new NavigatorCollection($embedded);
                }

                return new self($embedded);
            }

            return new NavigatorCollection();
        }

        return new self($embedded);
    }

    /**
     * Return all key/values.
     *
     * @return array
     */
    public function all()
    {
        $content = $this->content;

        unset($content[static::RELS], $content[static::EMBEDDED]);

        return $content;
    }
}
