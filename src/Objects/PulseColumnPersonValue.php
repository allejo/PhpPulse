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
            $this->setValue($this->jsonResponse);
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
     * @since 0.3.0 \InvalidArgumentException is now thrown
     * @since 0.1.0
     *
     * @throws \InvalidArgumentException if $user is not an integer, is not positive, or is not a PulseUser object
     */
    public function updateValue ($user)
    {
        PulseUser::_isCastable($user);

        $user       = ($user instanceof PulseUser) ? $user->getId() : $user;
        $url        = sprintf("%s/%d/columns/%s/person.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "user_id"  => $user
        );

        $result = self::sendPut($url, $postParams);
        $this->setValue($result);
    }

    /**
     * {@inheritdoc}
     */
    protected function isNullValue ()
    {
        // Thank you very much, DaPulse. Changing the return type of an API endpoint clearly does not break any existing
        // code. Check to see if an invalid user is returned to be able to return null

        return parent::isNullValue() ||
               (is_array($this->jsonResponse) &&
                array_key_exists('value', $this->jsonResponse) &&
                is_array($this->jsonResponse['value']) &&
                array_key_exists('id', $this->jsonResponse['value']) &&
                $this->jsonResponse['value']['id'] === 0);
    }

    protected function setValue ($response)
    {
        $this->column_value = new PulseUser($response["value"]["id"]);
    }
}