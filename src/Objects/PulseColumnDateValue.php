<?php

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
     * **Warning** This function may return a null value so ensure the returned value is not null before calling any
     * functions that belong to a DateTime object.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return \DateTime|null Null is returned when no date is listed in this date column
     */
    public function getValue ()
    {
        if ($this->isNullValue())
        {
            return null;
        }

        if (!isset($this->column_value))
        {
            $this->column_value = new \DateTime($this->jsonResponse["value"]);
        }

        return $this->column_value;
    }

    /**
     * Update the date of the date column. The specific time of the DateTime object will be ignored.
     *
     * @api
     *
     * @param \DateTime $dateTime The new date
     *
     * @since 0.1.0
     */
    public function updateValue ($dateTime)
    {
        $url        = sprintf("%s/%d/columns/%s/date.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "date_str" => date_format($dateTime, "Y-m-d")
        );

        self::sendPut($url, $postParams);

        $this->column_value = $dateTime;
    }
}