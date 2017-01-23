<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Objects\ApiObject;

class PulseUpdate extends ApiObject
{
    const API_PREFIX = "updates";

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
     * @var array
     */
    protected $assets;

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
     * @api
     * @since  0.1.0
     * @return PulseUser
     */
    public function getAuthor ()
    {
        $this->lazyLoad();
        self::lazyCast($this->user, "PulseUser");

        return $this->user;
    }

    /**
     * The resource's URL.
     *
     * @api
     * @since  0.1.0
     * @return string
     */
    public function getUrl ()
    {
        $this->lazyLoad();

        return $this->url;
    }

    /**
     * The update's id.
     *
     * @api
     * @since  0.1.0
     * @return string
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * The update's body.
     *
     * @api
     * @since  0.1.0
     * @return string
     */
    public function getBody ()
    {
        $this->lazyLoad();

        return $this->body;
    }

    /**
     * The update's body in plain text
     *
     * @api
     * @since  0.1.0
     * @return string
     */
    public function getBodyText ()
    {
        $this->lazyLoad();

        return $this->body_text;
    }

    /**
     * The replies made to this update.
     *
     * @api
     * @since  0.1.0
     * @return static[]
     */
    public function getReplies ()
    {
        $this->lazyLoad();
        self::lazyCastAll($this->replies, "PulseUpdate");

        return $this->replies;
    }

    /**
     * The update's kind.
     *
     * @api
     * @since  0.1.0
     * @return string
     */
    public function getKind ()
    {
        $this->lazyLoad();

        return $this->kind;
    }

    /**
     * Retrieve whether or not this update has any attachments
     *
     * @api
     * @todo Remove at 0.4.0 or next major release
     * @deprecated 0.3.0 Use PulseUpdate::hasAssets(). To be removed in 0.4.0
     * @since  0.1.0
     * @return string
     */
    public function getHasAssets ()
    {
        return $this->hasAssets();
    }

    /**
     * Get an array of this update's assets
     *
     * Sample array structure of assets
     *
     * ```
     * array(
     *   0 => array(
     *     'account_id' => 115448
     *     'big_geometry' => '250x250'
     *     'created_at' => '2017-01-21T09:45:28Z'
     *     'crocodoc_status' => null
     *     'crocodoc_uuid' => null
     *     'crocodoc_viewable' => true
     *     'desc' => null
     *     'holder_id' => 23611844
     *     'holder_type' => 'Post'
     *     'id' => 2401793
     *     'large_geometry' => '250x250'
     *     'metadata' => Array ()
     *     'original_geometry' => '250x250'
     *     'resource_content_type' => 'image/png'
     *     'resource_file_name' => 'sample.png'
     *     'resource_file_size' => 6077
     *     'thumb_big_geometry' => '250x250'
     *     'thumb_geometry' => '150x150'
     *     'updated_at' => '2017-01-21T09:45:32Z'
     *     'uploaded_by_id' => 303448
     *   )
     * )
     * ```
     *
     * @api
     *
     * @since  0.3.0 Documentation has been corrected; this returns an array
     * @since  0.1.0
     *
     * @return array
     */
    public function getAssets ()
    {
        $this->lazyLoad();

        return $this->assets;
    }

    /**
     * Creation time.
     *
     * @api
     * @since  0.1.0
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
     * @since  0.1.0
     * @return \DateTime
     */
    public function getUpdatedAt ()
    {
        $this->lazyLoad();
        self::lazyCast($this->updated_at, '\DateTime');

        return $this->updated_at;
    }

    /**
     * Get the users watching this update
     *
     * @api
     * @todo Remove at 0.4.0 or next major release
     * @deprecated 0.3.0 This data is no longer provided by the DaPulse API; this function will be removed in the next
     *                   major release with planned replacement
     * @since  0.1.0
     * @return PulseUser[]
     */
    public function getWatchers ()
    {
        return [];
    }

    /**
     * Retrieve whether or not this update has any attachments
     *
     * @api
     *
     * @since  0.3.0 Previously was available as 'getHasAssets()'
     *
     * @return bool
     */
    public function hasAssets ()
    {
        $this->lazyLoad();

        return $this->has_assets;
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
     * @throws InvalidObjectException if this PulseUpdate has already been deleted
     */
    public function deleteUpdate ()
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
     *
     * @throws \InvalidArgumentException if $user is not an integer, is not positive, or is not a PulseUser object
     *
     * @return bool Returns true on success
     */
    public function likeUpdate ($user)
    {
        return $this->likeUnlikeUpdate($user, true);
    }

    /**
     * Have a user unlike an update
     *
     * @api
     *
     * @param int|PulseUser $user The user that will be liking/un-liking the update
     *
     * @since 0.1.0
     *
     * @throws \InvalidArgumentException if $user is not an integer, is not positive, or is not a PulseUser object
     *
     * @return bool Returns true on success
     */
    public function unlikeUpdate ($user)
    {
        return $this->likeUnlikeUpdate($user, false);
    }

    /**
     * Like and un-liking functionality
     *
     * @param int|PulseUser $user The user that will be liking/un-liking the update
     * @param bool          $like True to like the update, false to unlike
     *
     * @throws \InvalidArgumentException if $user is not an integer, is not positive, or is not a PulseUser object
     *
     * @return bool Returns true on success
     */
    private function likeUnlikeUpdate ($user, $like)
    {
        PulseUser::_isCastable($user);

        $user   = ($user instanceof PulseUser) ? $user->getId() : $user;
        $url    = sprintf("%s/%d/%s.json", self::apiEndpoint(), $this->getId(), (($like) ? "like" : "unlike"));
        $params = array(
            "user" => $user
        );

        return self::sendPost($url, $params);
    }

    // =================================================================================================================
    //   PulseUpdate functions
    // =================================================================================================================

    /**
     * Get all of the account's updates (ordered from newest to oldest)
     *
     * ```
     * array['since']         \DateTime - Get updates from a specific date
     *      ['until']         \DateTime - Get updates until a specific date
     *      ['updated_since'] \DateTime - Get updates that were edited or replied to after a specific date
     *      ['updated_until'] \DateTime - Get updates that were edited or replied to before a specific date
     * ```
     *
     * If you do not pass \DateTime objects, they should be strings of dates in the format, YYYY-mm-dd, or a unix timestamp
     *
     * @api
     *
     * @param  array $params GET parameters passed to the URL (see above)
     *
     * @since  0.3.0 $params now accepts \DateTime objects and will be converted automatically. Strings will also try to
     *               be converted to Unix timestamps
     * @since  0.1.0
     *
     * @return PulseUpdate[]
     */
    public static function getUpdates ($params = [])
    {
        $url = sprintf("%s.json", self::apiEndpoint());
        $dateKeys = ['since', 'until', 'updated_since', 'updated_until'];

        foreach ($params as $key => &$value)
        {
            if (in_array($key, $dateKeys))
            {
                if ($value instanceof \DateTime)
                {
                    $value = date_format($value, 'U'); // A unix timestamp will allow for hours & minutes
                }
                else if (($unix = strtotime($value)))
                {
                    $value = $unix;
                }
            }
        }

        return self::fetchAndCastToObjectArray($url, 'PulseUpdate', $params);
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

        $url    = sprintf("%s.json", self::apiEndpoint());
        $params = array(
            "user"        => $user,
            "pulse"       => $pulse,
            "update_text" => $text
        );

        self::setIfNotNullOrEmpty($params, "announcement", $announceToAll);

        $result = self::sendPost($url, $params);

        return (new PulseUpdate($result));
    }
}