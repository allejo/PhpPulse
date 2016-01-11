<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Objects\ApiObject;

class PulseUpdate extends ApiObject
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

    // =================================================================================================================
    //   Getter functions
    // =================================================================================================================

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
     * Creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        self::lazyLoad($this->created_at, '\DateTime');

        return $this->created_at;
    }

    /**
     * Last update time.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        self::lazyLoad($this->updated_at, '\DateTime');

        return $this->updated_at;
    }

    /**
     * Get the users watching this update
     *
     * @return PulseUser[]
     */
    public function getWatchers ()
    {
        self::lazyArray($this->watched, "PulseUser");

        return $this->watched;
    }

    // =================================================================================================================
    //   Modification functions
    // =================================================================================================================

    /**
     * Delete this update
     *
     * @api
     *
     * @since  0.1.0
     *
     * @throws InvalidObjectException This PulseUpdate as already been deleted
     */
    public function deleteUpdate()
    {
        $this->checkInvalid();

        $url = sprintf("%s/%d.json", self::apiEndpoint(), $this->getId());
        self::sendDelete($url);

        $this->deletedObject = true;
    }

    // =================================================================================================================
    //   Liking functions
    // =================================================================================================================

    /**
     * Have a user like an update
     *
     * @api
     *
     * @param int|PulseUser $user The user that will be liking/un-liking the update
     *
     * @since 0.1.0
     */
    public function likeUpdate ($user)
    {
        $this->likeUnlikeUpdate($user, true);
    }

    /**
     * Have a user unlike an update
     *
     * @api
     *
     * @param int|PulseUser $user The user that will be liking/un-liking the update
     *
     * @since 0.1.0
     */
    public function unlikeUpdate ($user)
    {
        $this->likeUnlikeUpdate($user, false);
    }

    /**
     * Like and un-liking functionality
     *
     * @param int|PulseUser $user The user that will be liking/un-liking the update
     * @param bool          $like True to like the update, false to unlike
     */
    private function likeUnlikeUpdate ($user, $like)
    {
        if ($user instanceof PulseUser)
        {
            $user = $user->getId();
        }

        $url = sprintf("%s/%d/%s.json", self::apiEndpoint(), $this->getId(), (($like) ? "like" : "unlike"));
        $params = array(
            "user" => $user
        );

        self::sendPost($url, $params);
    }

    // =================================================================================================================
    //   PulseUpdate functions
    // =================================================================================================================

    /**
     * Get all of the account's updates (ordered from new to old)
     *
     * @api
     *
     * @param  array $params GET parameters passed to the URL
     *
     * @since  0.1.0
     *
     * @return PulseUpdate[]
     */
    public static function getUpdates ($params = array())
    {
        $url = sprintf("%s.json", self::apiEndpoint());

        return self::sendGet($url, $params);
    }

    /**
     * Create a new update
     *
     * @api
     *
     * @param  int|PulseUser $user          The author of this post
     * @param  int|Pulse     $pulse         The Pulse to whom this update will belong to
     * @param  string        $text          The content of the update
     * @param  null|bool     $announceToAll Whether or not to announce this update to everyone's wall
     *
     * @since  0.1.0
     *
     * @return PulseUpdate
     */
    public static function createUpdate ($user, $pulse, $text, $announceToAll = NULL)
    {
        if ($user instanceof PulseUser)
        {
            $user = $user->getId();
        }

        if ($pulse instanceof Pulse)
        {
            $pulse = $pulse->getId();
        }

        $url = sprintf("%s.json", self::apiEndpoint());
        $params = array(
            "user" => $user,
            "pulse" => $pulse,
            "update_text" => $text
        );

        self::setIfNotNullOrEmpty($params, "announcement", $announceToAll);

        $result = self::sendPost($url, $params);

        return (new PulseUpdate($result));
    }
}