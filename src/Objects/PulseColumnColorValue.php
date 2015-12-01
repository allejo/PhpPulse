<?php

namespace allejo\DaPulse\Objects;

/**
 * Class PulseColumnColorValue
 *
 * @package allejo\DaPulse\Objects
 */
class PulseColumnColorValue extends PulseColumnValue
{
    /**
     * The index of the orange status
     */
    const Orange = 0;

    /**
     * The index of the light green status
     */
    const L_Green = 1;

    /**
     * The index of the red status
     */
    const Red = 2;

    /**
     * The index of the blue status
     */
    const Blue = 3;

    /**
     * The index of the purple status
     */
    const Purple = 4;

    /**
     * The index of the grey status
     */
    const Grey = 5;

    /**
     * The index of the green status
     */
    const Green = 6;

    /**
     * The index of the light blue status
     */
    const L_Blue = 7;

    /**
     * The index of the gold status
     */
    const Gold = 8;

    /**
     * The index of the yellow status
     */
    const Yellow = 9;

    /**
     * The index of the black status
     */
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

        $url        = sprintf("%s/%d/columns/%s/status.json", parent::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id"    => $this->pulse_id,
            "color_index" => $color
        );

        self::sendPut($url, $postParams);

        $this->column_value = $color;
    }
}