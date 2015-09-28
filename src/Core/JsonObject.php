<?php

namespace allejo\DaPulse\Core;

abstract class JsonObject
{
    protected $jsonResponse;

    public function __construct ($jsonResponse)
    {
        $this->jsonResponse = $jsonResponse;

        foreach($this->jsonResponse as $key => $val)
        {
            if (property_exists(get_called_class(), $key))
            {
                $this->$key = $val;
            }
        }
    }
}