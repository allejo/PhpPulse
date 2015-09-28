<?php

/**
 * This class contains DaPulse SlimPulse class
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
class ApiSlimPulse extends ApiObject
{
    /**
     * The resource's URL.
     *
     * @var string
     */
    protected $url;

    /**
     * The pulse's unique identifier.
     *
     * @var int
     */
    protected $id;

    /**
     * The pulse's name.
     *
     * @var string
     */
    protected $name;

    /**
     * The board's subscribers.
     *
     * @var array of subscribers.
     */
    protected $subscribers;

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

    public function getSubscribers ()
    {
        return $this->subscribers;
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
