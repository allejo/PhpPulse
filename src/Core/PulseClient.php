<?php

namespace allejo\DaPulse\Core;

use allejo\DaPulse\Utilities\UrlQuery;

abstract class PulseClient
{
    const API_PROTOCOL = "https";
    const API_ENDPOINT = "api.dapulse.com";
    const API_VERSION  = "v1";
    const API_PREFIX   = "";

    private $apiKey;
    private $params;

    public function __construct ($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->params['api_key'] = $this->apiKey;
    }

    protected function sendGet ($url, $params)
    {
        $params = (!is_null($params)) ? array_merge($this->params, $params) : $this->params;

        $urlQuery = new UrlQuery($url, $params);

        return $urlQuery->sendGet();
    }

    protected static function apiEndpoint ()
    {
        return sprintf("%s://%s/%s/%s", self::API_PROTOCOL, self::API_ENDPOINT, self::API_VERSION, static::API_PREFIX);
    }
}