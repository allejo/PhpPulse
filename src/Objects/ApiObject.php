<?php

/**
 * This file contains the ApiObject class
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Utilities\UrlQuery;

/**
 * The base class for all DaPulse API objects
 *
 * @internal
 * @package allejo\DaPulse\Objects
 * @since   0.1.0
 */
abstract class ApiObject
{
    /**
     * The namespace used for all main PhpPulse objects. This is value is prepended before PhpPulse objects when being
     * checked with `instanceof`.
     *
     * @internal
     */
    const OBJ_NAMESPACE = "\\allejo\\DaPulse\\";

    /**
     * The default API protocol used for URL calls
     *
     * @internal
     */
    const API_PROTOCOL = "https";

    /**
     * The API end point for URL calls
     *
     * @internal
     */
    const API_ENDPOINT = "api.dapulse.com";

    /**
     * The API version used for URL calls
     *
     * @internal
     */
    const API_VERSION  = "v1";

    /**
     * The suffix that is appended to the URL to access functionality for certain objects
     *
     * @internal
     */
    const API_PREFIX   = "";

    /**
     * The API key used to make the URL calls
     *
     * @var string
     */
    protected static $apiKey;

    /**
     * When set to true, the object can only be constructed from an associative array of data. It will not attempt
     * to fetch the data with an API call; this is intended for objects are not directly accessible via the API.
     *
     * @var bool
     */
    protected $arrayConstructionOnly = false;

    /**
     * Set to true if the object has been deleted via an API call but the instance still exists. This variable will
     * prevent further API calls to a nonexistent object.
     *
     * @var bool
     */
    protected $deletedObject = false;

    /**
     * An associative array representing the original JSON response from DaPulse
     *
     * @var array
     */
    protected $jsonResponse;

    /**
     * Create an object from an API call
     *
     * @param int|array $idOrArray Either the numerical ID of an object or an associative array representing a JSON
     *                             response from an API call
     *
     * @throw \InvalidArgumentException The specified object cannot be created directly from an API call but instead
     *        requires an associative array of information gathered from other API calls.
     *
     * @since 0.1.0
     */
    public function __construct ($idOrArray)
    {
        $urlEndPoint = "";

        if (!is_array($idOrArray))
        {
            $urlEndPoint = sprintf("%s/%d.json", self::apiEndpoint(), $idOrArray);
        }

        if ($this->arrayConstructionOnly && !is_array($idOrArray))
        {
            throw new \InvalidArgumentException("A " . get_called_class() . " cannot be fetched from an ID.");
        }

        $this->jsonResponse = (is_array($idOrArray)) ? $idOrArray : $this::sendGet($urlEndPoint);

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
     * Assign an associative array from a JSON response and map them to instance variables
     *
     * @since 0.1.0
     */
    protected function assignResults ()
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
     * Check if the current object has been marked as deleted from DaPulse. If so, throw an exception.
     *
     * @throws InvalidObjectException
     */
    protected function checkInvalid ()
    {
        if ($this->deletedObject)
        {
            throw new InvalidObjectException("This object no longer exists on DaPulse", 2);
        }
    }

    /**
     * Inject data into the array that will be mapped into individual instance variables. This function must be called
     * **before** lazyArray() is called and maps the associative array to objects.
     *
     * @param array $target An array of associative arrays with data to be converted into objects
     * @param array $array  An associative array containing data to be merged with the key being the name of the
     *                      instance variable.
     *
     * @since 0.1.0
     *
     * @throw \InvalidArgumentException If either parameters are not arrays
     */
    protected static function lazyInject (&$target, $array)
    {
        if (!is_array($target) || !is_array($array))
        {
            throw new \InvalidArgumentException("Both the target and array must be arrays");
        }

        // If the first element is an array, let's assume $target hasn't been lazily casted into objects
        if (is_array($target[0]))
        {
            foreach ($target as &$element)
            {
                $element = array_merge($element, $array);
            }
        }
    }

    /**
     * Convert the specified item into the specified object if needed
     *
     * @param mixed  $target     The item to check
     * @param string $objectType The class name of the Objects the items should be
     *
     * @since 0.1.0
     */
    protected static function lazyLoad (&$target, $objectType)
    {
        if (self::lazyLoadConversionNeeded($target, $objectType))
        {
            $target = new $objectType($target);
        }
    }

    /**
     * Check if an individual item needs to be lazily converted into an object
     *
     * @param  mixed  $target     The item to check
     * @param  string $objectType The class name of the Objects the items should be
     *
     * @since  0.1.0
     *
     * @return bool
     */
    protected static function lazyLoadConversionNeeded ($target, $objectType)
    {
        $objectDefinition = "\\allejo\\DaPulse\\" . $objectType;

        return !($target instanceof $objectDefinition);
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
        $firstItem = $array[0];

        return self::lazyLoadConversionNeeded($firstItem, $objectType);
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
     * Store the value in an array if the value is not null. This function is a shortcut of setting values in an array
     * only if they are not null, if not leave them unset; used ideally for PUT requests.
     *
     * @param array  $array The array that will store all of the POST parameters
     * @param string $name  The name of the field
     * @param string $value The value to be stored in a given field
     */
    protected static function setIfNotNullOrEmpty (&$array, $name, $value)
    {
        if (!is_null($value) || !empty($value))
        {
            $array[$name] = $value;
        }
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
     * Send a POST request to a specified URL
     *
     * @param  string $url
     * @param  array  $postParams
     * @param  array  $getParams
     *
     * @since  0.1.0
     *
     * @return mixed
     */
    protected static function sendPost ($url, $postParams, $getParams = array())
    {
        return self::sendRequest("POST", $url, $postParams, $getParams);
    }

    /**
     * Send a PUT request to a specified URL
     *
     * @param  string $url
     * @param  array  $postParams
     * @param  array  $getParams
     *
     * @since  0.1.0
     *
     * @return mixed
     */
    protected static function sendPut ($url, $postParams, $getParams = array())
    {
        return self::sendRequest("PUT", $url, $postParams, $getParams);
    }

    /**
     * Send a DELETE request to a specified URL
     *
     * @param  string $url
     * @param  array  $getParams
     *
     * @since  0.1.0
     *
     * @return mixed
     */
    protected static function sendDelete ($url, $getParams = array())
    {
        return self::sendRequest("DELETE", $url, null, $getParams);
    }

    /**
     * Get the base URL to use in all of the API calls
     *
     * @param  string|null $apiPrefix If the API end point is different from the class's constant, this value will be
     *                                used as the suffix for the API endpoint
     *
     * @since  0.1.0
     *
     * @return string The base URL to call
     */
    protected static function apiEndpoint ($apiPrefix = NULL)
    {
        $apiSection = isset($apiPrefix) ? $apiPrefix : static::API_PREFIX;

        return sprintf("%s://%s/%s/%s", self::API_PROTOCOL, self::API_ENDPOINT, self::API_VERSION, $apiSection);
    }

    /**
     * Send the appropriate URL request
     *
     * @param  string $type
     * @param  string $url
     * @param  array  $postParams
     * @param  array  $getParams
     *
     * @since  0.1.0
     *
     * @return mixed
     */
    private static function sendRequest ($type, $url, $postParams, $getParams)
    {
        $getParams["api_key"] = self::$apiKey;

        $urlQuery = new UrlQuery($url, $getParams);

        switch ($type)
        {
            case "POST":
                return $urlQuery->sendPost($postParams);

            case "PUT":
                return $urlQuery->sendPut($postParams);

            case "DELETE":
                return $urlQuery->sendDelete();

            default:
                throw new \InvalidArgumentException();
        }
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