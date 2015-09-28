<?php

/**
 * This class contains DaPulse Update class
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
class ApiUpdate extends ApiObject
{
    /**
     * User who wrote the update.
     *
     * @var object
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


    public function getUser ()
    {
        return $this->user;
    }

    public function getUrl ()
    {
        return $this->url;
    }

    public function getId ()
    {
        return $this->id;
    }

    public function getBody ()
    {
        return $this->body;
    }

    public function getKind ()
    {
        return $this->kind;
    }

    public function getHasAssets ()
    {
        return $this->has_assets;
    }

    public function getAssets ()
    {
        return $this->assets;
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
