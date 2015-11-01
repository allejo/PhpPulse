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
        if (empty($this->value))
        {
            $this->value = new PulseUser($this->jsonResponse["value"]["id"]);
        }

        return $this->value;
    }

    public function updateValue ($userID)
    {
        $url = sprintf("%s/%d/columns/%s/person.json", parent::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "user_id" => $userID
        );

        self::sendPost($url, $postParams);

        $this->value = new PulseUser($userID);
    }
}