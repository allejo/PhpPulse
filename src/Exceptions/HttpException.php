<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Exceptions;

/**
 * An exception thrown if a cURL job returned an HTTP status of anything but 200
 *
 * @package allejo\DaPulse\Exceptions
 * @since   0.1.0
 */
class HttpException extends \Exception
{
    /**
     * Create an exception
     *
     * @param string $code      The HTTP code returned
     * @param string $response  A JSON formatted string containing information regarding the HTTP error or a string
     *                          simply containing stating the error.
     *
     * @since 0.1.0
     */
    public function __construct ($code, $response)
    {
        $json = json_decode($response, true);

        if (!is_null($json))
        {
            $message = (isset($json["message"])) ? $json["message"] : $json["error"];

            $this->message = sprintf("HTTP %d: %s", $code, $message);
        }
        else
        {
            $this->message = $response;
        }

        $this->code = $code;
    }
}
