<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiPulse;

class PulseProject extends ApiPulse
{
    const API_PREFIX = "pulses";

    private $urlSyntax = "%s/%s/%s.json";

    public function getSubscribers($params = array())
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "subscribers");

        return parent::fetchJsonArrayToObjectArray($url, "PulseUser", $params);
    }

    public function getNotes()
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "notes");

        return parent::fetchJsonArrayToObjectArray($url, $this->id, "PulseNote");
    }

    public static function getPulses($params = array())
    {
        $url = sprintf("%s.json", parent::apiEndpoint());

        return parent::fetchJsonArrayToObjectArray($url, "PulsePulse", $params);
    }
}