<?php

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\PulseUser;

/**
 * Class PulseColumnTextValue
 *
 * @internal
 * @package allejo\DaPulse\Objects
 */
class PulseColumnPersonValue extends PulseColumnValue
{
    public function getValue ()
    {
        if (!isset($this->column_value))
        {
            $this->column_value = new PulseUser($this->jsonResponse["value"]["id"]);
        }

        return $this->column_value;
    }

    public function updateValue ($userID)
    {
        $url = sprintf("%s/%d/columns/%s/person.json", parent::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "user_id" => $userID
        );

        self::sendPut($url, $postParams);

        $this->column_value = new PulseUser($userID);
    }
}