<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiUpdate;

class PulseUpdate extends ApiUpdate
{
    protected $watcherObjects;

    public function getWatchers ()
    {
        if (is_null($this->watcherObjects))
        {
            $this->watcherObjects = parent::jsonArrayToObjectArray("PulseUser", $this->getWatcherIds());
        }

        return $this->watcherObjects;
    }
}