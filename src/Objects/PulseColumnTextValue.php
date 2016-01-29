<?php

namespace allejo\DaPulse\Objects;

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
     * @since  0.1.0
     *
     * @return string The column's content
     */
    public function getValue ()
    {
        if (!isset($this->column_value))
        {
            $this->column_value = $this->jsonResponse["value"];
        }

        return $this->column_value;
    }

    /**
     * Update the text of a text column
     *
     * @api
     *
     * @param string $text
     *
     * @since 0.1.0
     */
    public function updateValue ($text)
    {
        $url        = sprintf("%s/%d/columns/%s/text.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "text"     => $text
        );

        self::sendPut($url, $postParams);

        $this->column_value = $text;
    }
}