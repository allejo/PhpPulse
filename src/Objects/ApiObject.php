<?php

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\Utilities\UrlQuery;

/**
 * The base class for all DaPulse API objects
 *
 * @package allejo\DaPulse\Objects
 * @since   0.1.0
 */
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

    /**
     * Access the JSON response from DaPulse directly
     *
     * @since  0.1.0
     *
     * @return array
     */
    public function getJson ()
    {
        return $this->jsonResponse;
    }

    /**
     * Go through the JSON response and assign all supported instance variables
     *
     * @since 0.1.0
     */
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

    /**
     * Convert the specified array into an array of object types if needed
     *
     * @param  string $objectType The class name of the Objects the items should be
     * @param  array  $array      The array to check
     *
     * @since  0.1.0
     */
    protected static function lazyArray (&$array, $objectType)
    {
        if (self::lazyArrayConversionNeeded($objectType, $array))
        {
            $array = self::jsonArrayToObjectArray($objectType, $array);
        }
    }

    /**
     * Check whether it is required for an array of JSON data to be converted into an array of the specified objects
     *
     * @param  string $objectType The class name of the Objects the items should be
     * @param  array  $array      The array to check
     *
     * @since  0.1.0
     *
     * @return bool True if the array needs to converted into an array of objects
     */
    protected static function lazyArrayConversionNeeded ($objectType, $array)
    {
        return (is_array($array[0]) && !($array instanceof $objectType));
    }

    /**
     * Fetches a JSON array and convert them into an array of objects
     *
     * @param  string $url       The API endpoint to call to get the JSON response from
     * @param  string $className The class name of the Object type to cast to
     * @param  array  $params    An associative array of URL parameters that will be passed to the specific call. For
     *                           example, limiting the number of results or the pagination of results. **Warning** The API
     *                           key does NOT need to be passed here
     *
     * @since  0.1.0
     *
     * @return array
     */
    protected static function fetchJsonArrayToObjectArray ($url, $className, $params = array())
    {
        $objects = self::sendGet($url, $params);

        return self::jsonArrayToObjectArray($className, $objects);
    }

    /**
     * Convert an array of associative arrays into a specific object
     *
     * @param  string $className The class name of the Object type
     * @param  array  $objects   An associative array to be converted into an object
     *
     * @since  0.1.0
     *
     * @return array An array of the specified objects
     */
    protected static function jsonArrayToObjectArray ($className, $objects)
    {
        $class = self::OBJ_NAMESPACE . $className;
        $array = array();

        foreach ($objects as $post)
        {
            $array[] = new $class($post);
        }

        return $array;
    }

    /**
     * Send a GET request to fetch the data from the specified URL
     *
     * @param  string $url    The API endpoint to call
     * @param  array  $params An associative array of URL parameters that will be passed to the specific call. For
     *                        example, limiting the number of results or the pagination of results. **Warning** The API
     *                        key does NOT need to be passed here
     *
     * @since  0.1.0
     *
     * @return mixed          An associative array of the JSON response from DaPulse
     */
    protected static function sendGet ($url, $params = array())
    {
        $params["api_key"] = self::$apiKey;

        $urlQuery = new UrlQuery($url, $params);

        return $urlQuery->sendGet();
    }

    /**
     * Get the base URL to use in all of the API calls
     *
     * @since  0.1.0
     *
     * @return string The base URL to call
     */
    protected static function apiEndpoint ()
    {
        return sprintf("%s://%s/%s/%s", self::API_PROTOCOL, self::API_ENDPOINT, self::API_VERSION, static::API_PREFIX);
    }

    /**
     * Set the API for all calls to the API
     *
     * @param string $apiKey The API key used to access the DaPulse API
     *
     * @since 0.1.0
     */
    public static function setApiKey ($apiKey)
    {
        self::$apiKey = $apiKey;
    }
}