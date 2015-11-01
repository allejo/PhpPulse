<?php

namespace allejo\DaPulse\Objects;

/**
 * Class PulseColumnTextValue
 *
 * @internal
 * @package allejo\DaPulse\Objects
 */
class PulseColumnTextValue extends PulseColumnValue
{
    public function getValue ()
    {
        if (!isset($this->column_value))
        {
            $this->column_value = $this->jsonResponse["value"];
        }

        return $this->column_value;
    }

    public function updateValue ($text)
    {
        $url = sprintf("%s/%d/columns/%s/text.json", parent::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "text" => $text
        );

        self::sendPost($url, $postParams);

        $this->column_value = $text;
    }
}