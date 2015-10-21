<?php

/**
 * This file contains the definition of all of the Pulse elements returned by the API
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;
use allejo\DaPulse\PulseUser;

/**
 * A "partial" class that contains the Pulse API elements and their appropriate get methods
 *
 * @since 0.1.0
 */
abstract class ApiPulse extends ApiObject
{
    /**
     * The resource's URL.
     *
     * @var string
     */
    protected $url;
    /**
     * The pulse's unique identifier.
     *
     * @var int
     */
    protected $id;
    /**
     * The pulse's name.
     *
     * @var string
     */
    protected $name;
    /**
     * The board's subscribers.
     *
     * @var PulseUser[]
     */
    protected $subscribers;
    /**
     * The amount of updates a pulse has.
     *
     * @var int
     */
    protected $updates_count;
    /**
     * The ID of the parent board.
     *
     * @var int
     */
    protected $board_id;
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
     * The resource's URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * The pulse's unique identifier.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The pulse's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The board's subscribers.
     *
     * @return PulseUser[]
     */
    public function getSubscribers()
    {
        self::lazyArray($this->subscribers, "PulseUser");

        return $this->subscribers;
    }

    /**
     * The amount of updates a pulse has.
     *
     * @return int
     */
    public function getUpdatesCount()
    {
        return $this->updates_count;
    }

    /**
     * The ID of the parent board.
     *
     * @return int
     */
    public function getBoardId()
    {
        return $this->board_id;
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
}
