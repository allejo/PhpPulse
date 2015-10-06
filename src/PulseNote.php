<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiNote;

class PulseNote extends ApiNote
{
    const API_PREFIX = "pulses";

    private $urlSyntax = "%s/%s/notes/%s.json";

    public function __construct($array)
    {
        $this->arrayConstructionOnly = true;

        parent::__construct($array);
    }

    public function editNote ($title = null, $content = null, $user_id = null, $create_update = null)
    {
        $url = sprintf($this->urlSyntax, self::apiEndpoint(), $this->getProjectId(), $this->getId());
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
}