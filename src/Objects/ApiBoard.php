<?php

/**
 * This class contains DaPulse Board class
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Core\JsonObject;

/**
 *
 *
 * @since 0.1.0
 */
class PulseBoard extends JsonObject
{
    /**
     * The resource's URL.
     *
     * @var string
     */
    protected $url;

    /**
     * The board's unique identifier.
     *
     * @var int
     */
    protected $id;

    /**
     * The board's name.
     *
     * @var string
     */
    protected $name;

    /**
     * The board's description.
     *
     * @var string
     */
    protected $description;

    /**
     * The board's visible columns.
     *
     * @var array
     */
    protected $columns;

    /**
     * The board's visible groups.
     *
     * @var array
     */
    protected $groups;

    /**
     * Creation time.
     *
     * @var \DateTime
     */
    protected $created_at;

    /**
     * Last update time.
     *
     * @var \DateTime
     */
    protected $updated_at;

    /**
     * The board's pulses.
     *
     * @var array of board items.
     */
    protected $pulses;


    public function getUrl ()
    {
        return $this->url;
    }

    public function getId ()
    {
        return $this->id;
    }

    public function getName ()
    {
        return $this->name;
    }

    public function getDescription ()
    {
        return $this->description;
    }

    public function getColumns ()
    {
        return $this->columns;
    }

    public function getGroups ()
    {
        return $this->groups;
    }

    public function getCreatedAt ()
    {
        return $this->created_at;
    }

    public function getUpdatedAt ()
    {
        return $this->updated_at;
    }

    public function getPulses ()
    {
        return $this->pulses;
    }
}
