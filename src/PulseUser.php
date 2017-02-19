<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiObject;

/**
 * The PulseUser class contains all of the functions related to accessing information about a user
 *
 * @api
 * @package allejo\DaPulse
 * @since   0.1.0
 */
class PulseUser extends ApiObject
{
    const API_PREFIX = "users";

    /**
     * The resource's URL.
     *
     * @var string
     */
    protected $url;

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
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getUrl ()
    {
        $this->lazyLoad();

        return $this->url;
    }

    /**
     * The user's unique identifier.
     *
     * @api
     *
     * @since  0.1.0
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
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getName ()
    {
        $this->lazyLoad();

        return $this->name;
    }

    /**
     * The user's email.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getEmail ()
    {
        $this->lazyLoad();

        return $this->email;
    }

    /**
     * The user's photo_url.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getPhotoUrl ()
    {
        $this->lazyLoad();

        return $this->photo_url;
    }

    /**
     * The user's title.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getTitle ()
    {
        $this->lazyLoad();

        return $this->title;
    }

    /**
     * The user's position.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getPosition ()
    {
        $this->lazyLoad();

        return $this->position;
    }

    /**
     * The user's phone.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getPhone ()
    {
        $this->lazyLoad();

        return $this->phone;
    }

    /**
     * The user's location.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getLocation ()
    {
        $this->lazyLoad();

        return $this->location;
    }

    /**
     * The user's status.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getStatus ()
    {
        $this->lazyLoad();

        return $this->status;
    }

    /**
     * The user's birthday.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getBirthday ()
    {
        $this->lazyLoad();

        return $this->birthday;
    }

    /**
     * True if the user is guest, false otherwise
     *
     * @api
     * @todo Remove this function at 0.4.0 or next breaking release
     * @deprecated 0.3.0 Use PulseUser::isGuest() instead
     * @since  0.1.0
     * @return bool
     */
    public function getIsGuest ()
    {
        return $this->isGuest();
    }

    /**
     * The user's skills.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string[]
     */
    public function getSkills ()
    {
        $this->lazyLoad();

        return $this->skills;
    }

    /**
     * Creation time.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return \DateTime
     */
    public function getCreatedAt ()
    {
        $this->lazyLoad();
        self::lazyCast($this->created_at, '\DateTime');

        return $this->created_at;
    }

    /**
     * Last update time.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return \DateTime
     */
    public function getUpdatedAt ()
    {
        $this->lazyLoad();
        self::lazyCast($this->updated_at, '\DateTime');

        return $this->updated_at;
    }

    /**
     * True if the user is guest, false otherwise
     *
     * @api
     *
     * @since  0.3.0
     *
     * @return bool
     */
    public function isGuest ()
    {
        $this->lazyLoad();

        return $this->is_guest;
    }

    /**
     * Get the user's newsfeed
     *
     * @api
     *
     * @param  array $params GET parameters that need to be passed in the URL
     *
     * @since  0.1.0
     *
     * @return PulseUpdate[] An array of PulseUpdates that make up the user's newsfeed
     */
    public function getNewsFeed ($params = [])
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "newsfeed");

        return parent::fetchAndCastToObjectArray($url, "PulseUpdate", $params);
    }

    /**
     * Get the user's posts
     *
     * @api
     *
     * @param  array $params GET parameters that need to be passed in the URL
     *
     * @since  0.1.0
     *
     * @return PulseUpdate[] An array of PulseUpdates for each of the posts
     */
    public function getPosts ($params = [])
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "posts");

        return parent::fetchAndCastToObjectArray($url, "PulseUpdate", $params);
    }

    /**
     * Get the user's unread posts
     *
     * @api
     *
     * @param  array $params GET parameters that need to be passed in the URL
     *
     * @since  0.1.0
     *
     * @return PulseUpdate[] An array of PulseUpdates for each of the posts
     */
    public function getUnreadFeed ($params = [])
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "unread_feed");

        return parent::fetchAndCastToObjectArray($url, "PulseUpdate", $params);
    }

    /**
     * Get all of the users
     *
     * @api
     *
     * @param  array $params GET parameters that need to be passed in the URL
     *
     * @since  0.1.0
     *
     * @return PulseUser[] An array of PulseUsers for each of the users
     */
    public static function getUsers ($params = [])
    {
        $url = sprintf("%s.json", parent::apiEndpoint());

        return parent::fetchAndCastToObjectArray($url, "PulseUser", $params);
    }

    // =================================================================================================================
    //   Convenience functions
    // =================================================================================================================

    /**
     * Check whether a given value can be casted or used to get a user ID
     *
     * @internal
     *
     * @param int|PulseUser $user
     *
     * @since 0.3.0
     *
     * @throws \InvalidArgumentException if $user is not an integer, is not positive, or is not a PulseUser object
     */
    public static function _isCastable ($user)
    {
        if ((!is_int($user) || (is_int($user) && $user < 1)) && !($user instanceof PulseUser))
        {
            throw new \InvalidArgumentException('$user is expected to be a positive integer or a PulseUser object');
        }
    }

    /**
     * @internal
     *
     * @param  int|PulseUser $user
     *
     * @since  0.3.0
     *
     * @return int
     */
    public static function _castToInt ($user)
    {
        self::_isCastable($user);

        return ($user instanceof PulseUser) ? $user->getId() : $user;
    }
}