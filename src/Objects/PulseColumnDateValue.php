<?php

namespace allejo\DaPulse\Objects;

use Symfony\Component\Validator\Constraints\DateTime;

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
        if (empty($this->value))
        {
            $this->value = new DateTime($this->jsonResponse["value"]);
        }

        return $this->value;
    }

    /**
     * @param DateTime $dateTime
     */
    public function updateValue ($dateTime)
    {
        $url = sprintf("%s/%d/columns/%s/text.json", parent::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "date_str" => date_format($dateTime, "Y-m-d")
        );

        self::sendPost($url, $postParams);

        $this->value = $dateTime;
    }
}