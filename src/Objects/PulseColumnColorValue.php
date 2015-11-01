<?php

namespace allejo\DaPulse\Objects;

/**
 * Class PulseColumnColorValue
 *
 * @internal
 * @package allejo\DaPulse\Objects
 */
class PulseColumnColorValue extends PulseColumnValue
{
    const Orange = 0;
    const Light_Green = 1;
    const Red = 2;
    const Blue = 3;
    const Purple = 4;
    const Grey = 5;
    const Green = 6;
    const Light_Blue = 7;
    const Gold = 8;
    const Yellow = 9;
    const Black = 10;

    public function getValue ()
    {
        if (!isset($this->column_value))
        {
            $this->column_value = $this->jsonResponse["value"]["index"];
        }

        return $this->column_value;
    }

    public function updateValue ($color)
    {
        if ($color < 0 && $color > 10)
        {
            throw new \InvalidArgumentException("DaPulse only has color indexes from 0-10");
        }

        $url = sprintf("%s/%d/columns/%s/status.json", parent::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "color_index" => $color
        );

        self::sendPost($url, $postParams);

        $this->column_value = $color;
    }
}