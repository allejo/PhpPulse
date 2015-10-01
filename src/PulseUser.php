<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiUser;

class PulseUser extends ApiUser
{
    const API_PREFIX = "users";

    private $urlSyntax = "%s/%s/%s.json";

    public function getNewsFeed ($params = array())
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "newsfeed");

        return parent::fetchJsonArrayToObjectArray($url, "PulseUpdate", $params);
    }

    public function getPosts ($params = array())
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "posts");

        return parent::fetchJsonArrayToObjectArray($url, "PulseUpdate", $params);
    }

    public function getUnreadFeed ($params = array())
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "unread_feed");

        return parent::fetchJsonArrayToObjectArray($url, "PulseUpdate", $params);
    }

    public static function getUsers($params = array())
    {
        $url = sprintf("%s.json", parent::apiEndpoint());

        return parent::fetchJsonArrayToObjectArray($url, "PulseUser", $params);
    }
}