<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Exceptions;

/**
 * An exception thrown if cURL were to face an issue while processing a request
 *
 * @package allejo\DaPulse\Exceptions
 * @since   0.1.0
 */
class CurlException extends \Exception
{
    /**
     * Create a new exception
     *
     * @param resource $cURLObject The cURL object used when cURL faced an issue
     *
     * @since 0.1.0
     */
    public function __construct ($cURLObject)
    {
        $this->code    = curl_errno($cURLObject);
        $this->message = sprintf("cURL Error %d: %s", $this->code, curl_error($cURLObject));
    }
}
