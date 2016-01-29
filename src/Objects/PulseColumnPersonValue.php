<?php

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\PulseUser;

/**
 * Class PulseColumnTextValue
 *
 * @package allejo\DaPulse\Objects
 */
class PulseColumnPersonValue extends PulseColumnValue
{
    public function getValue ()
    {
        if (is_null($this->jsonResponse["value"]))
        {
            return null;
        }

        if (!isset($this->column_value))
        {
            $this->column_value = new PulseUser($this->jsonResponse["value"]["id"]);
        }

        return $this->column_value;
    }

    public function updateValue ($userId)
    {
        $url        = sprintf("%s/%d/columns/%s/person.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "user_id"  => $userId
        );

        self::sendPut($url, $postParams);

        $this->column_value = new PulseUser($userId);
    }
}