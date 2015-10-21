<?php

/**
 * This file contains the definition of all of the User elements returned by the API
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

/**
 * A "partial" class that contains the User API elements and their appropriate get methods
 *
 * @since 0.1.0
 */
abstract class ApiUser extends ApiObject
{
    /**
     * The resource's URL.
     *
     * @var string
     */
    protected $url;
    /**
     * The user's unique identifier.
     *
     * @var int
     */
    protected $id;
    /**
     * The user's name.
     *
     * @var string
     */
    protected $name;
    /**
     * The user's email.
     *
     * @var string
     */
    protected $email;
    /**
     * The user's photo_url.
     *
     * @var string
     */
    protected $photo_url;
    /**
     * The user's title.
     *
     * @var string
     */
    protected $title;
    /**
     * The user's position.
     *
     * @var string
     */
    protected $position;
    /**
     * The user's phone.
     *
     * @var string
     */
    protected $phone;
    /**
     * The user's location.
     *
     * @var string
     */
    protected $location;
    /**
     * The user's status.
     *
     * @var string
     */
    protected $status;
    /**
     * The user's birthday.
     *
     * @var string
     */
    protected $birthday;
    /**
     * True if the user is guest, false otherwise
     *
     * @var bool
     */
    protected $is_guest;
    /**
     * The user's skills.
     *
     * @var string[]
     */
    protected $skills;
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
     * The user's unique identifier.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The user's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The user's email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * The user's photo_url.
     *
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photo_url;
    }

    /**
     * The user's title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * The user's position.
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * The user's phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * The user's location.
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * The user's status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * The user's birthday.
     *
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * True if the user is guest, false otherwise
     *
     * @return bool
     */
    public function getIsGuest()
    {
        return $this->is_guest;
    }

    /**
     * The user's skills.
     *
     * @return string[]
     */
    public function getSkills()
    {
        return $this->skills;
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
