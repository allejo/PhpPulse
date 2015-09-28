<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiUpdate;
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

    public function getPosts ($params = array())
    {
        $url = sprintf("%s/%s/posts.json", parent::apiEndpoint(), $this->id);
        $posts = $this->sendGet($url, $params);
        $updates = array();

        foreach ($posts as $post)
        {
            $updates[] = new ApiUpdate($post);
        }

        return $updates;
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