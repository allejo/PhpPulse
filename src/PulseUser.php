<?php

/**
 * This file contains the PulseUser class
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiObject;

/**
 * The PulseUser class contains all of the functions related to accessing information about a user
 *
 * @package allejo\DaPulse
 * @since   0.1.0
 */
class PulseUser extends ApiObject
{
    /**
     * {@inheritdoc}
     */
    const API_PREFIX = "users";

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
     * The membership type of this user with respect to a specific Pulse or PulseBoard. This value will not be set if
     * a PulseUser object is created standalone and not by another object which supports subscribers or membership.
     *
     * @var mixed
     */
    protected $membership;

    /**
     * The URL pattern used for all calls
     *
     * @var string
     */
    private $urlSyntax = "%s/%s/%s.json";

    // ================================================================================================================
    //   Getter functions
    // ================================================================================================================

    /**
     * The resource's URL.
     *
     * @return string
     */
    public function getUrl ()
    {
        return $this->url;
    }

    /**
     * The user's unique identifier.
     *
     * @return int
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * The user's name.
     *
     * @return string
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * The user's email.
     *
     * @return string
     */
    public function getEmail ()
    {
        return $this->email;
    }

    /**
     * The user's photo_url.
     *
     * @return string
     */
    public function getPhotoUrl ()
    {
        return $this->photo_url;
    }

    /**
     * The user's title.
     *
     * @return string
     */
    public function getTitle ()
    {
        return $this->title;
    }

    /**
     * The user's position.
     *
     * @return string
     */
    public function getPosition ()
    {
        return $this->position;
    }

    /**
     * The user's phone.
     *
     * @return string
     */
    public function getPhone ()
    {
        return $this->phone;
    }

    /**
     * The user's location.
     *
     * @return string
     */
    public function getLocation ()
    {
        return $this->location;
    }

    /**
     * The user's status.
     *
     * @return string
     */
    public function getStatus ()
    {
        return $this->status;
    }

    /**
     * The user's birthday.
     *
     * @return string
     */
    public function getBirthday ()
    {
        return $this->birthday;
    }

    /**
     * True if the user is guest, false otherwise
     *
     * @return bool
     */
    public function getIsGuest ()
    {
        return $this->is_guest;
    }

    /**
     * The user's skills.
     *
     * @return string[]
     */
    public function getSkills ()
    {
        return $this->skills;
    }

    /**
     * Creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt ()
    {
        self::lazyLoad($this->created_at, '\DateTime');

        return $this->created_at;
    }

    /**
     * Last update time.
     *
     * @return \DateTime
     */
    public function getUpdatedAt ()
    {
        self::lazyLoad($this->updated_at, '\DateTime');

        return $this->updated_at;
    }

    /**
     * This function is now a placeholder until it can be removed since the DaPulse API no longer supports this
     * functionality
     *
     * @api
     *
     * @deprecated 0.2.0
     *
     * @since   0.1.0
     *
     * @returns string An empty string
     */
    public function getMembership ()
    {
        return "";
    }

    /**
     * Get the user's newsfeed
     *
     * @param  array $params GET parameters that need to be passed in the URL
     *
     * @since  0.1.0
     *
     * @return PulseUpdate[] An array of PulseUpdates that make up the user's newsfeed
     */
    public function getNewsFeed ($params = array())
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "newsfeed");

        return parent::fetchJsonArrayToObjectArray($url, "PulseUpdate", $params);
    }

    /**
     * Get the user's posts
     *
     * @param  array $params GET parameters that need to be passed in the URL
     *
     * @since  0.1.0
     *
     * @return PulseUpdate[] An array of PulseUpdates for each of the posts
     */
    public function getPosts ($params = array())
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "posts");

        return parent::fetchJsonArrayToObjectArray($url, "PulseUpdate", $params);
    }

    /**
     * Get the user's unread posts
     *
     * @param  array $params GET parameters that need to be passed in the URL
     *
     * @since  0.1.0
     *
     * @return PulseUpdate[] An array of PulseUpdates for each of the posts
     */
    public function getUnreadFeed ($params = array())
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "unread_feed");

        return parent::fetchJsonArrayToObjectArray($url, "PulseUpdate", $params);
    }

    /**
     * Get all of the users
     *
     * @param  array $params GET parameters that need to be passed in the URL
     *
     * @since  0.1.0
     *
     * @return PulseUser[] An array of PulseUsers for each of the users
     */
    public static function getUsers ($params = array())
    {
        $url = sprintf("%s.json", parent::apiEndpoint());

        return parent::fetchJsonArrayToObjectArray($url, "PulseUser", $params);
    }
}