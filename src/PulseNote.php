<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiNote;

class PulseNote extends ApiNote
{
    public function __construct($array)
    {
        if (!is_array($array))
        {
            throw new \InvalidArgumentException("A PulseNote cannot be fetched from an ID.");
        }

        parent::__construct($array);
    }

    public function updateNote ($title = null, $content = null, $user_id = null, $create_update = null)
    {

    }
}