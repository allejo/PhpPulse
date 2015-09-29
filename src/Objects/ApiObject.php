<?php

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\Utilities\UrlQuery;

abstract class ApiObject
{
    const OBJ_NAMESPACE = "\\allejo\\DaPulse\\";
    const API_PROTOCOL = "https";
    const API_ENDPOINT = "api.dapulse.com";
    const API_VERSION  = "v1";
    const API_PREFIX   = "";

    protected static $apiKey;

    protected $urlEndPoint;
    protected $jsonResponse;

    public function __construct ($idOrArray)
    {
        if (!is_array($idOrArray))
        {
            $this->urlEndPoint = sprintf("%s/%d.json", self::apiEndpoint(), $idOrArray);
        }

        $this->jsonResponse = (is_array($idOrArray)) ? $idOrArray : $this::sendGet($this->urlEndPoint);

        $this->assignResults();
    }

    public function getJson ()
    {
        return $this->jsonResponse;
    }

    private function assignResults ()
    {
        foreach($this->jsonResponse as $key => $val)
        {
            if (property_exists(get_called_class(), $key))
            {
                $this->$key = $val;
            }
        }
    }

    protected static function returnArrayOfItems ($url, $className, $params = array())
    {
        $posts = self::sendGet($url, $params);

        return self::convertToArrayOfItems($className, $posts);
    }

    protected static function convertToArrayOfItems ($className, $objects)
    {
        $class = self::OBJ_NAMESPACE . $className;
        $array = array();

        foreach ($objects as $post)
        {
            $array[] = new $class($post);
        }

        return $array;
    }

    protected static function sendGet ($url, $params = array())
    {
        $params["api_key"] = self::$apiKey;

        $urlQuery = new UrlQuery($url, $params);

        return $urlQuery->sendGet();
    }

    protected static function apiEndpoint ()
    {
        return sprintf("%s://%s/%s/%s", self::API_PROTOCOL, self::API_ENDPOINT, self::API_VERSION, static::API_PREFIX);
    }

    public static function setApiKey ($apiKey)
    {
        self::$apiKey = $apiKey;
    }
}