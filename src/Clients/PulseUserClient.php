<?php

namespace allejo\DaPulse\Clients;

use allejo\DaPulse\Core\PulseClient;
use allejo\DaPulse\PulseUser;

class PulseUserClient extends PulseClient
{
    const API_PREFIX = "users";

    public function fetchUser ($id, $params = NULL)
    {
        $url = sprintf("%s/%d.json", parent::apiEndpoint(), $id);
        $response = $this->sendGet($url, $params);

        return (new PulseUser($response));
    }
}