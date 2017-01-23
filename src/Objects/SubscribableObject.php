<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\PulseUser;

/**
 * A class representing a Pulse object which supports subscribers
 *
 * @internal
 * @package allejo\DaPulse\Objects
 * @since   0.1.0
 */
abstract class SubscribableObject extends ApiObject
{
    /**
     * The objects's unique identifier.
     *
     * @var int
     */
    protected $id;

    /**
     * @var PulseUser[]
     */
    protected $subscribers;

    /**
     * The objects's unique identifier.
     *
     * @return int
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * Get the users who are subscribed to this object
     *
     * ```
     * array['page']     int - Page offset to fetch
     *      ['per_page'] int - Number of results to return per page
     *      ['offset']   int - Pad a number of results
     * ```
     *
     * @api
     *
     * @param  array $params     GET parameters passed to the URL (see above)
     * @param  bool  $forceFetch When set to true, this will force an API call to retrieve the subscribers. Otherwise,
     *                           this'll return the cached subscribers.
     *
     * @since  0.1.0
     *
     * @return PulseUser[]
     */
    public function getSubscribers ($params = array(), $forceFetch = false)
    {
        if (is_null($this->subscribers) || $forceFetch)
        {
            $url = sprintf("%s/%d/subscribers.json", $this::apiEndpoint(), $this->getId());
            $this->subscribers = self::fetchAndCastToObjectArray($url, "PulseUser", $params);
        }

        self::lazyCastAll($this->subscribers, 'PulseUser');

        return $this->subscribers;
    }

    /**
     * Subscriber a user to a object
     *
     * @api
     *
     * @param  int|PulseUser $userId  The user that will be subscribed to the board
     * @param  bool|null     $asAdmin Set to true if the user will be an admin of the board
     *
     * @since  0.3.0 A PulseUser object of the newly added subscriber is returned
     * @since  0.1.0
     *
     * @return PulseUser
     */
    public function addSubscriber ($userId, $asAdmin = NULL)
    {
        $userId = ($userId instanceof PulseUser) ? $userId->getId() : $userId;
        $url    = sprintf("%s/%d/subscribers.json", self::apiEndpoint(), $this->getId());
        $params = array(
            "user_id" => $userId
        );

        self::setIfNotNullOrEmpty($params, "as_admin", $asAdmin);
        $newSubscriber = self::sendPut($url, $params);

        // Save the user to the local cache
        if (is_null($this->subscribers))
        {
            $this->getSubscribers();
        }

        $user = new PulseUser($newSubscriber);
        $this->subscribers[] = $user;

        return $user;
    }

    /**
     * Unsubscribe a user from this object
     *
     * @api
     *
     * @param  int|PulseUser $userId The user that will be unsubscribed from the board
     *
     * @since  0.3.0 A PulseUser object of the removed subscriber is returned
     * @since  0.1.0
     *
     * @return PulseUser
     */
    public function removeSubscriber ($userId)
    {
        $userId = ($userId instanceof PulseUser) ? $userId->getId() : $userId;
        $url    = sprintf("%s/%d/subscribers/%d.json", self::apiEndpoint(), $this->getId(), $userId);
        $result = self::sendDelete($url);

        // Remove the user from the local cache
        if (is_null($this->subscribers))
        {
            $this->getSubscribers();
        }

        foreach ($this->subscribers as $key => $subscriber)
        {
            if ($subscriber->getId() == $result['id'])
            {
                unset($this->subscribers[$key]);
            }
        }

        return (new PulseUser($result));
    }
}