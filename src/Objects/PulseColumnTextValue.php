<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\Exceptions\ColumnNotFoundException;

/**
 * Class PulseColumnTextValue
 *
 * @package allejo\DaPulse\Objects
 * @since   0.1.0
 */
class PulseColumnTextValue extends PulseColumnValue
{
    /**
     * Get a text column's content
     *
     * @api
     *
     * @since  0.4.0 ColumnNotFoundException is now thrown
     * @since  0.1.0
     *
     * @throws ColumnNotFoundException The specified column ID does not exist for the parent Pulse
     *
     * @return string|null The column's content
     */
    public function getValue ()
    {
        return parent::getValue();
    }

    /**
     * Update the text of a text column
     *
     * @api
     *
     * @param string $text
     *
     * @since 0.3.0 \InvalidArgumentException is now thrown
     * @since 0.1.0
     *
     * @throws \InvalidArgumentException if $text does not have a string representation
     */
    public function updateValue ($text)
    {
        if (!is_scalar($text) || (is_object($text) && method_exists($text, '__toString')))
        {
            throw new \InvalidArgumentException('$text is expected to have a string representation');
        }

        $url        = sprintf("%s/%d/columns/%s/text.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = [
            "pulse_id" => $this->pulse_id,
            "text"     => (string)$text
        ];

        $result = self::sendPut($url, $postParams);
        $this->jsonResponse = $result;
        $this->setValue($result);
    }

    protected function setValue ($response)
    {
        $this->column_value = $response["value"];
    }
}