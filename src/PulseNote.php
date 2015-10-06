<?php

namespace allejo\DaPulse;

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
        $url = $this->getNotesUrl();
        $postParams = array(
            "id" => $this->getProjectId(),
            "note_id" => $this->getId()
        );

        self::setIfNotNull($postParams, "title", $title);
        self::setIfNotNull($postParams, "content", $content);
        self::setIfNotNull($postParams, "user_id", $user_id);
        self::setIfNotNull($postParams, "create_update", $create_update);

        $this->jsonResponse = self::sendPut($url, $postParams);
        $this->assignResults();

        return $this;
    }

    public function deleteNote ()
    {
        self::sendDelete($this->getNotesUrl());
    }

    private function getNotesUrl ()
    {
        return sprintf("%s/%s/notes/%s.json", self::apiEndpoint(), $this->getProjectId(), $this->getId());
    }
}