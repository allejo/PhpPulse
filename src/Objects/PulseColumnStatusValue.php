<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

/**
 * Class PulseColumnColorValue
 *
 * @package allejo\DaPulse\Objects
 * @since   0.1.0
 */
class PulseColumnStatusValue extends PulseColumnValue
{
    const DEFAULT_VALUE = self::Grey; // The default color for DaPulse columns

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
    const Gray = self::Grey; // just an alias

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

    /**
     * The lowest status value that exists
     */
    const MIN_VALUE = self::Orange;

    /**
     * The largest status value that exists
     */
    const MAX_VALUE = self::Black;

    /**
     * Get the numerical representation of the color that a status column is set to.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return int The color value of a column
     */
    public function getValue ()
    {
        return parent::getValue();
    }

    /**
     * Update the status of a status column
     *
     * It is highly recommended that you use the constants available in the **PulseColumnColorValue** class to match the
     * colors; keep in mind this value cannot be higher than 11.
     *
     * @api
     *
     * @param int $color The numerical value of the new color value
     *
     * @see   PulseColumnStatusValue::Orange
     * @see   PulseColumnStatusValue::L_Green
     * @see   PulseColumnStatusValue::Red
     * @see   PulseColumnStatusValue::Blue
     * @see   PulseColumnStatusValue::Purple
     * @see   PulseColumnStatusValue::Grey
     * @see   PulseColumnStatusValue::Green
     * @see   PulseColumnStatusValue::L_Blue
     * @see   PulseColumnStatusValue::Gold
     * @see   PulseColumnStatusValue::Yellow
     * @see   PulseColumnStatusValue::Black
     *
     * @since 0.1.0
     *
     * @throws \InvalidArgumentException if the $color is not an int or is not between 0-10
     */
    public function updateValue ($color)
    {
        if ($color < 0 || $color > 10 || !is_int($color))
        {
            throw new \InvalidArgumentException("DaPulse only has color indexes from 0-10");
        }

        $url        = sprintf("%s/%d/columns/%s/status.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = [
            "pulse_id"    => $this->pulse_id,
            "color_index" => $color
        ];

        $result = self::sendPut($url, $postParams);
        $this->setValue($result);
    }

    /**
     * Get the hex value of the color used on DaPulse to represent the different statuses.
     *
     * @api
     *
     * @param  int $numericalValue The numerical value of the column
     *
     * @since  0.1.0
     *
     * @return string A hex value **without** the leading #
     */
    public static function getHexColor ($numericalValue)
    {
        $colorArray = self::getHexColors();

        return $colorArray[$numericalValue];
    }

    /**
     * Get an array of hex values for each of the statuses
     *
     * @api
     *
     * @since  0.3.1
     *
     * @return array
     */
    public static function getHexColors ()
    {
        return [
            self::Orange  => "fdab3d",
            self::L_Green => "00c875",
            self::Red     => "e2445c",
            self::Blue    => "0086c0",
            self::L_Blue  => "579bfc",
            self::Purple  => "a25ddc",
            self::Green   => "037f4c",
            self::Gold    => "CAB641",
            self::Yellow  => "FFCB00",
            self::Black   => "333333",
            self::Grey    => "c4c4c4"
        ];
    }

    protected function setValue ($response)
    {
        $value = $response['value'];

        // If the status column is set to 'Grey' or the default 'Just Assigned' value, DaPulse will evidently
        // return null... So let's set it to the Grey value to not confuse people
        $this->column_value = (is_array($value) && array_key_exists('index', $value)) ? $response["value"]["index"] : self::Grey;
    }
}
