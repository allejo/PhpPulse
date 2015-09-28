<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiUpdate;

class PulseUpdate extends ApiUpdate
{
    private $authorObject;
    private $watcherObjects;
    private $replyObjects;

    public function getBodyText ()
    {
        return $this->jsonResponse["body_text"];
    }

    public function getReplies ()
    {
        if (is_null($this->replyObjects))
        {
            $replies = $this->jsonResponse["replies"];
            $replyObjects = array();

            foreach ($replies as $reply)
            {
                $replyObjects[] = new PulseUpdate($reply);
            }

            $this->replyObjects = $replyObjects;
        }

        return $this->replyObjects;
    }

    public function getUser ()
    {
        if (is_null($this->authorObject))
        {
            $this->authorObject = new PulseUser($this->user);
        }

        return $this->authorObject;
    }

    public function getWatchers ()
    {
        if (is_null($this->watcherObjects))
        {
            $this->watcherObjects = parent::convertToArrayOfItems("PulseUser", $this->jsonResponse["watched"]);
        }

        return $this->watcherObjects;
    }

    public function getWatcherIDs ()
    {
        return $this->jsonResponse["watched"];
    }
}