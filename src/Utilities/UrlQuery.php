<?php

/**
 * This file contains the UrlQuery class which is a wrapper for cURL
 *
 * @copyright 2015 Vladimir Jimenez
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
 * @package allejo\DaPulse\Utilities
 * @since   0.1.0
 */
class UrlQuery
{
    /**
     * Send a POST or PUT body as JSON instead of URL encoded values
     */
    const BODY_AS_JSON = 0x1;

    /**
     * The API endpoint that will be used in all requests
     *
     * @var string
     */
    private $url;

    /**
     * The cURL object this class is a wrapper for
     *
     * @var resource
     */
    private $cURL;

    /**
     * Configure all of the authentication needed for cURL requests and the API endpoint
     *
     * @param string $url       The API endpoint this instance will be calling
     * @param array  $urlParams Parameters that will be appended to the URL as GET parameters
     *
     * @since 0.1.0
     */
    public function __construct ($url, $urlParams)
    {
        $this->url  = $url . "?" . self::formatParameters($urlParams);
        $this->cURL = curl_init();

        $this->configureCurl();
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
     * @since 0.1.0
     *
     * @throws \InvalidArgumentException Either the username or the password was an empty or null string
     */
    public function setAuthentication ($username, $password)
    {
        if (StringUtilities::isNullOrEmpty($username) || StringUtilities::isNullOrEmpty($password))
        {
            throw new \InvalidArgumentException("Both the username and password must be non-empty strings.");
        }

        curl_setopt_array($this->cURL, array(
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD  => $username . ":" . $password
        ));
    }

    /**
     * Set cURL headers
     *
     * @param array $headers The headers that will be sent with cURL
     *
     * @since 0.1.0
     *
     * @throws \InvalidArgumentException The $headers parameter was not an array or it was an empty array
     */
    public function setHeaders ($headers)
    {
        if (empty($headers) || !is_array($headers))
        {
            throw new \InvalidArgumentException("The headers parameter must be a non-empty array");
        }

        curl_setopt_array($this->cURL, array(
            CURLOPT_HEADER     => true,
            CURLOPT_HTTPHEADER => $headers
        ));
    }

    /**
     * Send a GET request
     *
     * @since  0.1.0
     *
     * @return mixed  An associative array matching the returned JSON result
     */
    public function sendGet ()
    {
        return $this->handleQuery();
    }

    /**
     * Send a POST request
     *
     * @param  array    $postArray The data that will be sent to DaPulse
     * @param  null|int $flags     Available flags: BODY_AS_JSON
     *
     * @since  0.1.0
     *
     * @throws HttpException
     *
     * @return mixed An associative array matching the returned JSON result
     */
    public function sendPost ($postArray, $flags = null)
    {
        $this->setPostFields($postArray, $flags);

        curl_setopt_array($this->cURL, array(
            CURLOPT_POST          => true,
            CURLOPT_CUSTOMREQUEST => "POST"
        ));

        return $this->handleQuery();
    }

    /**
     * Send a PUT request
     *
     * @param  array    $postArray The data that will be sent to DaPulse
     * @param  null|int $flags     Available flags: BODY_AS_JSON
     *
     * @since  0.1.0
     *
     * @return mixed  An associative array matching the returned JSON result
     */
    public function sendPut ($postArray, $flags = null)
    {
        $this->setPostFields($postArray, $flags);

        curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, "PUT");

        return $this->handleQuery();
    }

    /**
     * Send a DELETE request
     *
     * @since  0.1.0
     *
     * @return mixed  An associative array matching the returned JSON result
     */
    public function sendDelete ()
    {
        curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, "DELETE");

        return $this->handleQuery();
    }

    /**
     * Set the POST fields that will be submitted in the cURL request
     *
     * @param string   $postArray The POST fields that will be sent to DaPulse
     * @param null|int $flags     Available flags: BODY_AS_JSON
     *
     * @since 0.1.0
     */
    private function setPostFields ($postArray, $flags)
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
     * Handle the execution of the cURL request. This function will also save the returned HTTP headers and handle them
     * appropriately.
     *
     * @since  0.1.0
     *
     * @throws CurlException If cURL is misconfigured or encounters an error
     * @throws HttpException An HTTP status of something other 200 is returned
     *
     * @return mixed
     */
    private function handleQuery ()
    {
        $result   = $this->executeCurl();
        $httpCode = curl_getinfo($this->cURL, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200 && $httpCode !== 201)
        {
            throw new HttpException($httpCode, $result);
        }

        return json_decode($result, true);
    }

    /**
     * Configure the cURL instance and its credentials for Basic Authentication that this instance will be working with
     *
     * @since 0.1.0
     */
    private function configureCurl ()
    {
        curl_setopt_array($this->cURL, array(
            CURLOPT_URL            => $this->url,
            CURLOPT_RETURNTRANSFER => true
        ));
    }

    /**
     * Execute the finalized cURL object that has already been configured
     *
     * @since  0.1.0
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
     * @since  0.1.0
     *
     * @return string         A URL encoded and combined array of GET or POST parameters to be sent
     */
    private static function formatParameters ($params)
    {
        $parameters = array();

        foreach ($params as $key => $value)
        {
            if (is_bool($value))
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
     * @since  0.1.0
     *
     * @return array
     */
    private static function formatArray ($prefix, $array)
    {
        $parameters = array();

        foreach ($array as $key => $value)
        {
            $arrayKey              = sprintf("%s[%s]", $prefix, $key);
            $parameters[$arrayKey] = $value;
        }

        return $parameters;
    }
}
