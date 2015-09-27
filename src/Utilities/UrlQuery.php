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
 * There's no need to access this class outside of this library as the appropriate functionality is properly wrapped in
 * the appropriate Pulse classes.
 *
 * @package allejo\DaPulse\Utilities
 * @since   0.1.0
 */
class UrlQuery
{
    /**
     * The default protocol the DaPulse API expects
     */
    const DEFAULT_PROTOCOL = "https";

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
     * @param  array  $postArray        The data that will be sent to DaPulse
     *
     * @since  0.1.0
     *
     * @return mixed  An associative array matching the returned JSON result
     */
    public function sendPost ($postArray)
    {
        $this->setPostFields($postArray);

        curl_setopt_array($this->cURL, array(
            CURLOPT_POST => true,
            CURLOPT_CUSTOMREQUEST => "POST"
        ));

        return $this->handleQuery();
    }

    /**
     * Send a PUT request
     *
     * @param  array  $postArray        The data that will be sent to DaPulse
     *
     * @since  0.1.0
     *
     * @return mixed  An associative array matching the returned JSON result
     */
    public function sendPut ($postArray)
    {
        $this->setPostFields($postArray);

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
     * @param string $postArray The POST fields that will be sent to DaPulse
     *
     * @since 0.1.0
     */
    private function setPostFields ($postArray)
    {
        $postData = self::formatParameters($postArray);

        curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $postData);
    }

    /**
     * Handle the execution of the cURL request. This function will also save the returned HTTP headers and handle them
     * appropriately.
     *
     * @since  0.1.0
     *
     * @throws \allejo\DaPulse\Exceptions\CurlException If cURL is misconfigured or encounters an error
     * @throws \allejo\DaPulse\Exceptions\HttpException An HTTP status of something other 200 is returned
     *
     * @return mixed
     */
    private function handleQuery ()
    {
        $result = $this->executeCurl();
        $httpCode = curl_getinfo($this->cURL, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200)
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
            CURLOPT_URL => $this->url,
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
     * @param  array    $params An array containing parameter names as keys and parameter values as values in the array.
     *
     * @since  0.1.0
     *
     * @return string           A URL encoded and combined array of GET parameters to be sent
     */
    private static function formatParameters ($params)
    {
        $parameters = array();

        foreach ($params as $key => $value)
        {
            $parameters[] = rawurlencode($key) . "=" . rawurlencode($value);
        }

        return implode("&", $parameters);
    }
}
