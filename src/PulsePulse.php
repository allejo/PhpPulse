<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiPulse;

class PulsePulse extends ApiPulse
{
    const API_PREFIX = "pulses";

    public static function getPulses($params = array())
    {
        $url = sprintf("%s.json", parent::apiEndpoint());

        return parent::returnArrayOfItems($url, "PulsePulse", $params);
    }
}