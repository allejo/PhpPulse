<?php

/**
 * This file contains the definition of all of the Board elements returned by the API
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

/**
 * A "partial" class that contains the Board API elements and their appropriate get methods
 *
 * @since 0.1.0
 */
class ApiBoard extends ApiObject
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

    /**
     * The resource's URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * The board's unique identifier.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The board's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The board's description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * The board's visible columns.
     *
     * @return array
     */
    public function getColumns()
    {
        self::lazyArray($this->columns, "PulseColumn");

        return $this->columns;
    }

    /**
     * The board's visible groups.
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Last update time.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * The board's pulses.
     *
     * @return array of board items.
     */
    public function getPulses()
    {
        return $this->pulses;
    }
}
