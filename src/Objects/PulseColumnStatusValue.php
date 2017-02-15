<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\Exceptions\ColumnNotFoundException;

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
     * The numerical value of the orange status
     */
    const Orange = 0;

    /**
     * The numerical value of the light green status
     */
    const L_Green = 1;

    /**
     * The numerical value of the red status
     */
    const Red = 2;

    /**
     * The numerical value of the blue status
     */
    const Blue = 3;

    /**
     * The numerical value of the purple status
     */
    const Purple = 4;

    /**
     * The numerical value of the grey status
     */
    const Grey = 5;
    const Gray = self::Grey; // just an alias

    /**
     * The numerical value of the green status
     */
    const Green = 6;

    /**
     * The numerical value of the light blue status
     */
    const L_Blue = 7;

    /**
     * The numerical value of the gold status
     */
    const Gold = 8;

    /**
     * The numerical value of the yellow status
     */
    const Yellow = 9;

    /**
     * The numerical value of the black status
     */
    const Black = 10;

    /**
     * The numerical value of the dark red status
     */
    const D_Red = 11;

    /**
     * The numerical value of the hot pink status
     */
    const Hot_Pink = 12;

    /**
     * The numerical value of the pink status
     */
    const Pink = 13;

    /**
     * The numerical value of the dark purple status
     */
    const D_Purple = 14;

    /**
     * The numerical value of the lime status
     */
    const Lime = 15;

    /**
     * The numerical value of the cyan status
     */
    const Cyan = 16;

    /**
     * The numerical value of the dark grey status
     */
    const D_Grey = 17;
    const D_Gray = self::D_Grey; // another alias

    /**
     * The numerical value of the brown status
     */
    const Brown = 18;

    /**
     * The numerical value of the dark orange status
     */
    const D_Orange = 19;

    /**
     * The lowest status value that exists
     */
    const MIN_VALUE = self::Orange;

    /**
     * The largest status value that exists
     */
    const MAX_VALUE = self::D_Orange;

    /**
     * Get the numerical representation of the color that a status column is set to.
     *
     * @api
     *
     * @since  0.4.0 ColumnNotFoundException is now thrown
     * @since  0.1.0
     *
     * @throws ColumnNotFoundException The specified column ID does not exist for the parent Pulse
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
     * colors; keep in mind this value cannot be higher than 19.
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
     * @see   PulseColumnStatusValue::D_Red
     * @see   PulseColumnStatusValue::Hot_Pink
     * @see   PulseColumnStatusValue::Pink
     * @see   PulseColumnStatusValue::D_Purple
     * @see   PulseColumnStatusValue::Lime
     * @see   PulseColumnStatusValue::Cyan
     * @see   PulseColumnStatusValue::D_Grey
     * @see   PulseColumnStatusValue::Brown
     * @see   PulseColumnStatusValue::D_Orange
     *
     * @since 0.1.0
     *
     * @throws \InvalidArgumentException if the $color is not an int or is not between 0-19
     */
    public function updateValue ($color)
    {
        if ($color < self::MIN_VALUE || $color > self::MAX_VALUE || !is_int($color))
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
            self::Orange   => 'fdab3d',
            self::L_Green  => '00c875',
            self::Red      => 'e2445c',
            self::Blue     => '0086c0',
            self::L_Blue   => '579bfc',
            self::Purple   => 'a25ddc',
            self::Green    => '037f4c',
            self::Gold     => 'CAB641',
            self::Yellow   => 'FFCB00',
            self::Black    => '333333',
            self::Grey     => 'c4c4c4',
            self::D_Red    => 'bb3354',
            self::Hot_Pink => 'ff158a',
            self::Pink     => 'ff5ac4',
            self::D_Purple => '784bd1',
            self::Lime     => '9cd326',
            self::Cyan     => '66ccff',
            self::D_Grey   => '808080',
            self::Brown    => '7f5347',
            self::D_Orange => 'ff642e',
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
