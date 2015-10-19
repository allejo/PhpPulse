<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Objects\ApiNote;

class PulseNote extends ApiNote
{
    const API_PREFIX = "pulses";

    public function __construct($array)
    {
        $this->arrayConstructionOnly = true;

        parent::__construct($array);
    }

    public function editNote ($title = null, $content = null, $user_id = null, $create_update = null)
    {
        $this->checkInvalid();

        $url = $this->getNotesUrl();
        $postParams = array(
            "id" => $this->getProjectId(),
            "note_id" => $this->getId()
        );

        self::setIfNotNullOrEmpty($postParams, "title", $title);
        self::setIfNotNullOrEmpty($postParams, "content", $content);
        self::setIfNotNullOrEmpty($postParams, "user_id", $user_id);
        self::setIfNotNullOrEmpty($postParams, "create_update", $create_update);

        $this->jsonResponse = self::sendPut($url, $postParams);
        $this->assignResults();

        return $this;
    }

    public function deleteNote ()
    {
        $this->checkInvalid();

        self::sendDelete($this->getNotesUrl());

        $this->deletedObject = true;
    }

    private function getNotesUrl ()
    {
        return sprintf("%s/%s/notes/%s.json", self::apiEndpoint(), $this->getProjectId(), $this->getId());
    }
}