<?php

namespace allejo\DaPulse\Tests;

class AuthenticatedClient
{
    private $apiToken;

    public function __construct ($filePath)
    {
        if (file_exists($filePath))
        {
            $file = file_get_contents($filePath, true);
            $json = json_decode($file, true);
            $this->apiToken = $json['apiToken'];
        }
        else if (getenv('apiToken') !== false)
        {
            $this->apiToken = getenv('apiToken');
        }
    }

    public function isAuthenticationSetup ()
    {
        return (isset($this->apiToken));
    }

    public function getApiToken()
    {
        return $this->apiToken;
    }
}