<?php

/**
 * This class contains DaPulse User class
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

/**
 *
 *
 * @since 0.1.0
 */
class ApiUser extends ApiObject
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

    public function getEmail ()
    {
        return $this->email;
    }

    public function getPhotoUrl ()
    {
        return $this->photo_url;
    }

    public function getTitle ()
    {
        return $this->title;
    }

    public function getPosition ()
    {
        return $this->position;
    }

    public function getPhone ()
    {
        return $this->phone;
    }

    public function getLocation ()
    {
        return $this->location;
    }

    public function getStatus ()
    {
        return $this->status;
    }

    public function getBirthday ()
    {
        return $this->birthday;
    }

    public function getIsGuest ()
    {
        return $this->is_guest;
    }

    public function getSkills ()
    {
        return $this->skills;
    }

    public function getCreatedAt ()
    {
        return $this->created_at;
    }

    public function getUpdatedAt ()
    {
        return $this->updated_at;
    }
}
