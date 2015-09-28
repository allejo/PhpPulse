<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiUser;

class PulseUser extends ApiUser
{
    const API_PREFIX = "users";

    public function __construct ($idOrArray)
    {
        if (!is_array($idOrArray))
        {
            $this->urlEndPoint = sprintf("%s/%d.json", parent::apiEndpoint(), $idOrArray);
        }

        parent::__construct($idOrArray);
    }

    public static function getUsers($params = array())
    {
        $url = sprintf("%s.json", parent::apiEndpoint());
        $users = self::sendGet($url, $params);
        $pulseUsers = array();

        foreach ($users as $user)
        {
            $pulseUsers[] = new static($user);
        }

        return $pulseUsers;
    }
}