<?php

/**
 * This file contains the PulseUser class
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Exceptions\IllegalAccessException;
use allejo\DaPulse\Objects\ApiUser;

/**
 * The PulseUser class contains all of the functions related to accessing information about a user
 *
 * @package allejo\DaPulse
 * @since   0.1.0
 */
class PulseUser extends ApiUser
{
    /**
     * {@inheritdoc}
     */
    const API_PREFIX = "users";

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

    /**
     *
     */
    public function getMembership ()
    {
        if (isset($this->membership))
        {
            return $this->membership;
        }

        throw new IllegalAccessException("This value is not accessible which means this user was not created in regards to another object.");
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