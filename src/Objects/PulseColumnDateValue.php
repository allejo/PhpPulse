<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

/**
 * Class PulseColumnTextValue
 *
 * @package allejo\DaPulse\Objects
 * @since   0.1.0
 */
class PulseColumnDateValue extends PulseColumnValue
{
    /**
     * Get the date listed in the date column
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return \DateTime|null Null is returned if there is no value set for this column
     */
    public function getValue ()
    {
        return parent::getValue();
    }

    /**
     * Update the date of the date column.
     *
     * The specific time of the DateTime object will be ignored.
     *
     * @api
     *
     * @param \DateTime $dateTime The new date
     *
     * @since 0.3.0 \InvalidArgumentException is now thrown
     * @since 0.1.0
     *
     * @throws \InvalidArgumentException if $dateTime is not a \DateTime
     */
    public function updateValue ($dateTime)
    {
        if (!($dateTime instanceof \DateTime))
        {
            throw new \InvalidArgumentException('$dateTime is expected to be of type \\DateTime');
        }

        $url        = sprintf("%s/%d/columns/%s/date.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "date_str" => date_format($dateTime, "Y-m-d")
        );

        $result = self::sendPut($url, $postParams);
        $this->setValue($result);
    }

    protected function setValue ($response)
    {
        // Another DaPulse API inconsistency. The data returned from a PUT request has the date in an array instead of
        // of a string.
        if (is_array($response['value']) && isset($response['value']['date']))
        {
            $this->column_value = new \DateTime($response['value']['date']);
            return;
        }

        $this->column_value = new \DateTime($response['value']);
    }
}