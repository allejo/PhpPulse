<?php

/**
 * @copyright 2019 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiObject;

class PulseTag extends ApiObject
{
    const API_PREFIX = "tags";

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var \DateTime
     */
    protected $created_at;

    /**
     * @var \DateTime
     */
    protected $updated_at;

    /**
     * @return string
     */
    public function getUrl ()
    {
        $this->lazyLoad();

        return $this->url;
    }

    /**
     * @return int
     */
    public function getId ()
    {
        $this->lazyLoad();

        return $this->id;
    }

    /**
     * @return string
     */
    public function getName ()
    {
        $this->lazyLoad();

        return $this->name;
    }

    /**
     * @return string
     */
    public function getColor ()
    {
        $this->lazyLoad();

        return $this->color;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt ()
    {
        $this->lazyLoad();
        self::lazyCast($this->created_at, '\DateTime');

        return $this->created_at;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt ()
    {
        $this->lazyLoad();
        self::lazyCast($this->updated_at, '\DateTime');

        return $this->updated_at;
    }
}
