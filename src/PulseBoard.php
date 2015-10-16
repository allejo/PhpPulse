<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiBoard;

class PulseBoard extends ApiBoard
{
    const API_PREFIX = "boards";

    public function deleteBoard ()
    {
        $this->checkInvalid();

        $url = sprintf("%s/%s.json", parent::apiEndpoint(), $this->getId());
        self::sendDelete($url);

        $this->deletedObject = true;
    }

    // ================================================================================================================
    //   Static functions
    // ================================================================================================================

    public static function createBoard ($user_id, $name, $description = null)
    {
        $url = sprintf("%s.json", parent::apiEndpoint());
        $postParams = array(
            "user_id" => $user_id,
            "name"    => $name
        );

        self::setIfNotNull($postParams, "description", $description);

        $boardResult = self::sendPost($url, $postParams);

        return (new PulseBoard($boardResult));
    }

    public static function getBoards ($params = array())
    {
        $url = sprintf("%s.json", parent::apiEndpoint());

        return parent::fetchJsonArrayToObjectArray($url, "PulseBoard", $params);
    }
}