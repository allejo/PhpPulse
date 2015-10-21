<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Objects\ApiObject;

class PulseGroup extends ApiObject
{
    const API_PREFIX = "boards";

    protected $id;
    protected $pos;
    protected $title;
    protected $color;
    protected $board_id;
    protected $archived;
    protected $deleted;

    public function __construct ($array)
    {
        $this->arrayConstructionOnly = true;

        parent::__construct($array);
    }

    public function getId ()
    {
        return $this->id;
    }

    public function getTitle ()
    {
        return $this->title;
    }

    public function getColor ()
    {
        return $this->color;
    }

    public function getBoardId ()
    {
        return $this->board_id;
    }

    public function isArchived ()
    {
        return $this->archived;
    }

    public function isDeleted ()
    {
        return $this->deleted;
    }

    /**
     * @throws InvalidObjectException The board ID is nonexistent. This typically occurs when a group has been archived
     *                                or deleted and the information is not accessible.
     */
    public function deleteGroup ()
    {
        if (!isset($this->board_id))
        {
            throw new InvalidObjectException("This group may have been archived or deleted; its parent board ID cannot be accessed", 3);
        }

        $this->checkInvalid();

        $url = sprintf("%s/%s/groups/%s.json", parent::apiEndpoint(), $this->getBoardId(), $this->getId());

        self::sendDelete($url);

        $this->deletedObject = true;
    }
}