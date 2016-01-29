<?php

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\PulseUser;

/**
 * Class PulseColumnTextValue
 *
 * @package allejo\DaPulse\Objects
 * @since   0.1.0
 */
class PulseColumnPersonValue extends PulseColumnValue
{
    /**
     * Get the person assigned listed in the person column
     *
     * **Warning** This function may return a null value so ensure the returned value is not null before calling any
     * functions that belong to a PulseUser object.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return PulseUser|null Null is returned when no person is listed in this person column
     */
    public function getValue ()
    {
        if ($this->isNullValue())
        {
            return null;
        }

        if (!isset($this->column_value))
        {
            $this->column_value = new PulseUser($this->jsonResponse["value"]["id"]);
        }

        return $this->column_value;
    }

    /**
     * Update the person in a person column
     *
     * @api
     *
     * @param int|PulseUser $user The new user that will be assigned to the person column
     *
     * @since 0.1.0
     */
    public function updateValue ($user)
    {
        $user       = ($user instanceof PulseUser) ? $user->getId() : $user;
        $url        = sprintf("%s/%d/columns/%s/person.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "user_id"  => $user
        );

        self::sendPut($url, $postParams);

        $this->column_value = new PulseUser($user);
    }
}