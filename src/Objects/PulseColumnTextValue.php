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
     * @return string|null The column's content
     */
    public function getValue ()
    {
        if ($this->isNullValue())
        {
            return null;
        }

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
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "text"     => (string)$text
        );

        self::sendPut($url, $postParams);

        $this->column_value = $text;
    }
}