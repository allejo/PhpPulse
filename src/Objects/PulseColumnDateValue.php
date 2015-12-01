<?php

namespace allejo\DaPulse\Objects;

/**
 * Class PulseColumnTextValue
 *
 * @internal
 * @package allejo\DaPulse\Objects
 */
class PulseColumnDateValue extends PulseColumnValue
{
    public function getValue ()
    {
        if (!isset($this->column_value))
        {
            $this->column_value = new DateTime($this->jsonResponse["value"]);
        }

        return $this->column_value;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function updateValue ($dateTime)
    {
        $url = sprintf("%s/%d/columns/%s/date.json", parent::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "date_str" => date_format($dateTime, "Y-m-d")
        );

        self::sendPut($url, $postParams);

        $this->column_value = $dateTime;
    }
}