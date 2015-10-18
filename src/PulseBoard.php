<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiBoard;
use allejo\DaPulse\Utilities\ArrayUtilities;

class PulseBoard extends ApiBoard
{
    const API_PREFIX = "boards";

    private $groupsFetched = false;

    // ================================================================================================================
    //   Columns functions
    // ================================================================================================================

    public function getColumns ()
    {

    }

    // ================================================================================================================
    //   Group functions
    // ================================================================================================================

    public function getGroups ($showArchived = false)
    {
        if (!$this->groupsFetched)
        {
            $fetchedGroups = $this->fetchGroups($showArchived);

            $this->groups = ArrayUtilities::array_merge_recursive_distinct($this->groups, $fetchedGroups);
        }

        self::lazyArray($this->groups, "PulseGroup");

        return $this->groups;
    }

    public function createGroup ($title)
    {
        $url = sprintf("%s/%s/groups.json", parent::apiEndpoint(), $this->getId());
        $postParams = array(
            "title" => $title
        );

        // The API doesn't return the board ID, so since we have access to it here: set it manually
        $groupResult = self::sendPost($url, $postParams);
        $groupResult["board_id"] = $this->id;

        return (new PulseGroup($groupResult));
    }

    private function fetchGroups ($showArchived)
    {
        $url = sprintf("%s/%s/groups.json", parent::apiEndpoint(), $this->getId());

        $this->groupsFetched = true;

        return self::sendGet($url, array(
            "show_archived" => $showArchived
        ));
    }

    // ================================================================================================================
    //   Board functions
    // ================================================================================================================

    public function deleteBoard ()
    {
        $this->checkInvalid();

        $url = sprintf("%s/%s.json", parent::apiEndpoint(), $this->getId());
        self::sendDelete($url);

        $this->deletedObject = true;
    }

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