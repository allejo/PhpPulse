<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Utilities;

use allejo\DaPulse\Exceptions\CurlException;
use allejo\DaPulse\Exceptions\HttpException;

/**
 * A wrapper class for working with cURL requests.
 *
 * This class configures cURL with all of the appropriate authentication information and proper cURL configuration for
 * processing requests.
 *
 * This class is provided as a convenience for all of the URL requests made by PhpPulse. This class may also be used
 * by external tools to make custom requests.
 *
 * @api
 * @since 0.4.0
 */
class HttpClient
{
    /**
     * Send a POST or PUT body as JSON instead of URL encoded values
     */
    const BODY_AS_JSON = 0x1;

    /** @var string */
    private $baseURL;

    /** @var array */
    private $query;

    /** @var array */
    private $headers;

    /** @var resource */
    private $cURL;

    /**
     * Configure all of the authentication needed for cURL requests and the API endpoint
     *
     * @param string $baseURL The API endpoint this instance will be calling
     * @param array  $options Parameters that will be appended to the URL as GET parameters
     *
     * @since 0.4.0
     */
    public function __construct ($baseURL, array $options = [])
    {
        $this->baseURL = $baseURL;
        $this->query = (isset($options['query'])) ? $options['query'] : [];
        $this->headers = (isset($options['headers'])) ? $options['headers'] : [];
        $this->cURL = curl_init();

        curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);

        $this->setHeaders($this->headers);
    }

    /**
     * Clean up after ourselves; clean up the cURL object.
     */
    public function __destruct ()
    {
        curl_close($this->cURL);
    }

    /**
     * Set the credentials for basic authentication
     *
     * @param string $username The username basic authentication
     * @param string $password The password basic authentication
     *
     * @since 0.4.0
     *
     * @throws \InvalidArgumentException Either the username or the password was an empty or null string
     */
    public function setAuthentication ($username, $password)
    {
        if (StringUtilities::isNullOrEmpty($username) || StringUtilities::isNullOrEmpty($password))
        {
            throw new \InvalidArgumentException("Both the username and password must be non-empty strings.");
        }

        curl_setopt_array($this->cURL, [
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD  => $username . ":" . $password
        ]);
    }

    /**
     * Set cURL headers
     *
     * @deprecated Use $options['header'] in the constructor instead
     *
     * @param array $headers The headers that will be sent with cURL
     *
     * @since 0.4.0
     */
    public function setHeaders (array $headers)
    {
        if (empty($headers))
        {
            return;
        }

        $curlReadyHeaders = [];

        foreach ($headers as $key => $value)
        {
            $curlReadyHeaders[] = "{$key}: {$value}";
        }

        curl_setopt_array($this->cURL, [
            CURLOPT_HTTPHEADER => $curlReadyHeaders
        ]);
    }

    /**
     * Set the POST fields that will be submitted in the cURL request
     *
     * @param array    $postArray The POST fields that will be sent to DaPulse
     * @param null|int $flags     Available flags: BODY_AS_JSON
     *
     * @since 0.4.0
     */
    private function setPostFields (array $postArray, $flags)
    {
        if ($flags & self::BODY_AS_JSON)
        {
            $postData = json_encode($postArray);
        }
        else
        {
            $postData = self::formatParameters($postArray);
        }

        curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $postData);
    }

    /**
     * Send a GET request
     *
     * @param string $endPoint    The API endpoint that'll be appended to the base URL from the constructor
     * @param array  $queryParams Key/value pairs that'll be passed in the query of the HTTP call
     *
     * @since  0.4.0
     *
     * @return mixed  An associative array matching the returned JSON result
     */
    public function get ($endPoint, array $queryParams)
    {
        return $this->executeQuery($endPoint, $queryParams);
    }

    /**
     * Send a POST request
     *
     * @param  string   $endPoint    The API endpoint that'll be appended to the base URL from the constructor
     * @param  array    $queryParams Key/value pairs that'll be passed in the query of the HTTP call
     * @param  array    $postArray   The data that will be sent in the body of the HTTP call
     * @param  null|int $flags       Available flags: BODY_AS_JSON
     *
     * @since  0.4.0
     *
     * @throws HttpException
     *
     * @return mixed An associative array matching the returned JSON result
     */
    public function post ($endPoint, array $queryParams, $postArray, $flags = null)
    {
        $this->setPostFields($postArray, $flags);

        curl_setopt_array($this->cURL, [
            CURLOPT_POST          => true,
            CURLOPT_CUSTOMREQUEST => "POST"
        ]);

        return $this->executeQuery($endPoint, $queryParams);
    }

    /**
     * Send a PUT request
     *
     * @param  string   $endPoint    The API endpoint that'll be appended to the base URL from the constructor
     * @param  array    $queryParams Key/value pairs that'll be passed in the query of the HTTP call
     * @param  array    $postArray   The data that will be sent in the body of the HTTP call
     * @param  null|int $flags       Available flags: BODY_AS_JSON
     *
     * @since  0.4.0
     *
     * @return mixed  An associative array matching the returned JSON result
     */
    public function put ($endPoint, array $queryParams, $postArray, $flags = null)
    {
        $this->setPostFields($postArray, $flags);

        curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, "PUT");

        return $this->executeQuery($endPoint, $queryParams);
    }

    /**
     * Send a DELETE request
     *
     * @param  string $endPoint    The API endpoint that'll be appended to the base URL from the constructor
     * @param  array  $queryParams Key/value pairs that'll be passed in the query of the HTTP call
     *
     * @since  0.4.0
     *
     * @return mixed  An associative array matching the returned JSON result
     */
    public function delete ($endPoint, array $queryParams)
    {
        curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, "DELETE");

        return $this->executeQuery($endPoint, $queryParams);
    }

    /**
     * Handle the execution of the cURL request. This function will also save the returned HTTP headers and handle them
     * appropriately.
     *
     * @param  string $endPoint    The API endpoint that'll be appended to the base URL from the constructor
     * @param  array  $queryParams Key/value pairs that'll be passed in the query of the HTTP call
     *
     * @since  0.4.0
     *
     * @throws CurlException If cURL is misconfigured or encounters an error
     * @throws HttpException An HTTP status of something other 200 is returned
     *
     * @return mixed
     */
    private function executeQuery ($endPoint, array $queryParams)
    {
        $mergedParams = array_merge($this->query, $queryParams);
        $url = $this->baseURL . $endPoint . '?' . self::formatParameters($mergedParams);

        curl_setopt($this->cURL, CURLOPT_URL, $url);

        $result   = $this->executeCurl();
        $httpCode = curl_getinfo($this->cURL, CURLINFO_HTTP_CODE);

        if ($httpCode != 200 && $httpCode != 201)
        {
            throw new HttpException($httpCode, $result);
        }

        return json_decode($result, true);
    }

    /**
     * Execute the finalized cURL object that has already been configured
     *
     * @since  0.4.0
     *
     * @throws \allejo\DaPulse\Exceptions\CurlException If cURL is misconfigured or encounters an error
     *
     * @return mixed
     */
    private function executeCurl ()
    {
        $result = curl_exec($this->cURL);

        if (!$result)
        {
            throw new CurlException($this->cURL);
        }

        return $result;
    }

    /**
     * Format an array into a URL encoded values to be submitted in cURL requests
     *
     * **Input**
     *
     * ```php
     * array(
     *     "foo"   => "bar",
     *     "param" => "value"
     * )
     * ```
     *
     * **Output**
     *
     * ```php
     * array(
     *     "foo=bar",
     *     "param=value"
     * )
     * ```
     *
     * @param  array $params An array containing parameter names as keys and parameter values as values in the array.
     *
     * @since  0.4.0
     *
     * @return string         A URL encoded and combined array of GET or POST parameters to be sent
     */
    public static function formatParameters ($params)
    {
        $parameters = [];

        foreach ($params as $key => $value)
        {
            if (is_null($value))
            {
                continue;
            }
            else if (is_bool($value))
            {
                $value = StringUtilities::booleanLiteral($value);
            }
            else if (is_array($value))
            {
                $formattedArray = self::formatArray($key, $value);
                $parameters[]   = self::formatParameters($formattedArray);

                continue;
            }

            $parameters[] = rawurlencode($key) . "=" . rawurlencode($value);
        }

        return implode("&", $parameters);
    }

    /**
     * Convert an indexed array into an array that can be feed to `formatParameters()` to be formatted to an acceptable
     * structure to be sent via a GET or POST request.
     *
     * **Input**
     *
     * ```php
     * array(
     *     "first",
     *     "second",
     *     "third"
     * )
     * ```
     *
     * **Output**
     *
     * ```php
     * array(
     *     "prefix[0]" => "first",
     *     "prefix[1]" => "second",
     *     "prefix[2]" => "third",
     * )
     * ```
     *
     * @param  string   $prefix The name of the
     * @param  string[] $array
     *
     * @see    formatParameters()
     *
     * @since  0.4.0
     *
     * @return array
     */
    private static function formatArray ($prefix, $array)
    {
        $parameters = [];

        foreach ($array as $key => $value)
        {
            $arrayKey              = sprintf("%s[%s]", $prefix, $key);
            $parameters[$arrayKey] = $value;
        }

        return $parameters;
    }
}
