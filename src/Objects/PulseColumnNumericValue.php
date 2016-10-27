<?php

namespace allejo\DaPulse\Objects;

/**
 * Class PulseColumnTextValue
 *
 * @package allejo\DaPulse\Objects
 * @since   0.2.0
 */
class PulseColumnNumericValue extends PulseColumnValue
{
    /**
     * Get a text column's content
     *
     * @api
     *
     * @since  0.2.0
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
     * @param int|double $text
     *
     * @since 0.2.0
     */
    public function updateValue ($text)
    {
        $url        = sprintf("%s/%d/columns/%s/numeric.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "value"    => $text
        );

        self::sendPut($url, $postParams);

        $this->column_value = $text;
    }
}