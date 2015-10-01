<?php

/**
 * This file contains the definition of all of the Update elements returned by the API
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\PulseUser;

/**
 * A "partial" class that contains the Update API elements and their appropriate get methods
 *
 * @since 0.1.0
 */
class ApiUpdate extends ApiObject
{
    /**
     * User who wrote the update.
     *
     * @var array|PulseUser
     */
    protected $user;
    /**
     * The resource's URL.
     *
     * @var string
     */
    protected $url;
    /**
     * The update's id.
     *
     * @var string
     */
    protected $id;
    /**
     * The update's body.
     *
     * @var string
     */
    protected $body;
    /**
     * The update's body in plain text
     *
     * @var string
     */
    protected $body_text;
    /**
     * The replies made to this update.
     *
     * @var array
     */
    protected $replies;
    /**
     * The update's kind.
     *
     * @var string
     */
    protected $kind;
    /**
     * The update's has_assets.
     *
     * @var string
     */
    protected $has_assets;
    /**
     * The update's assets.
     *
     * @var string
     */
    protected $assets;
    /**
     * The users who watch this update.
     *
     * @var array
     */
    protected $watched;
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
     * User who wrote the update.
     *
     * @return PulseUser
     */
    public function getUser()
    {
        self::lazyLoad($this->user, "PulseUser");

        return $this->user;
    }

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
     * The update's id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The update's body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * The update's body in plain text
     *
     * @return string
     */
    public function getBodyText()
    {
        return $this->body_text;
    }

    /**
     * The replies made to this update.
     *
     * @return static[]
     */
    public function getReplies()
    {
        self::lazyArray($this->replies, "PulseUpdate");

        return $this->replies;
    }

    /**
     * The update's kind.
     *
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * The update's has_assets.
     *
     * @return string
     */
    public function getHasAssets()
    {
        return $this->has_assets;
    }

    /**
     * The update's assets.
     *
     * @return string
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * The IDs of users who are watching this update.
     *
     * @return array
     */
    public function getWatcherIds()
    {
        return $this->watched;
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
