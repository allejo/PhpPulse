<?php

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
     * @api
     *
     * @param  array $params
     *
     * @since  0.1.0
     *
     * @return PulseUser[]
     */
    public function getSubscribers ($params = array())
    {
        $url = sprintf("%s/%d/subscribers.json", $this::apiEndpoint(), $this->getId());

        return self::fetchJsonArrayToObjectArray($url, "PulseUser", $params);
    }

    /**
     * Subscriber a user to a object
     *
     * @api
     *
     * @param int|PulseUser $userId  The user that will be subscribed to the board
     * @param bool|null     $asAdmin Set to true if the user will be an admin of the board
     *
     * @since 0.1.0
     */
    public function addSubscriber ($userId, $asAdmin = NULL)
    {
        if ($userId instanceof PulseUser)
        {
            $userId = $userId->getId();
        }

        $url    = sprintf("%s/%d/subscribers.json", self::apiEndpoint(), $this->getId());
        $params = array(
            "user_id" => $userId
        );

        self::setIfNotNullOrEmpty($params, "as_admin", $asAdmin);
        self::sendPut($url, $params);
    }

    /**
     * Unsubscribe a user from this object
     *
     * @api
     *
     * @param int|PulseUser $userId The user that will be unsubscribed from the board
     *
     * @since 0.1.0
     */
    public function removeSubscriber ($userId)
    {
        if ($userId instanceof PulseUser)
        {
            $userId = $userId->getId();
        }

        $url = sprintf("%s/%d/subscribers/%d.json", self::apiEndpoint(), $this->getId(), $userId);

        self::sendDelete($url);
    }
}