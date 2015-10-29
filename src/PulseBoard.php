<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiObject;
use allejo\DaPulse\Utilities\ArrayUtilities;

class PulseBoard extends ApiObject
{
    const API_PREFIX = "boards";

    // ================================================================================================================
    //   Instance Variables
    // ================================================================================================================

    /**
     * The resource's URL.
     *
     * @var string
     */
    protected $url;

    /**
     * The board's unique identifier.
     *
     * @var int
     */
    protected $id;

    /**
     * The board's name.
     *
     * @var string
     */
    protected $name;

    /**
     * The board's description.
     *
     * @var string
     */
    protected $description;

    /**
     * The board's visible columns.
     *
     * @var array
     */
    protected $columns;

    /**
     * The board's visible groups.
     *
     * @var array
     */
    protected $groups;

    /**
     * Creation time.
     *
     * @var \DateTime
     */
    protected $created_at;

    /**
     * Last update time.
     *
     * @var \DateTime
     */
    protected $updated_at;

    /**
     * Whether or not groups have been fetched. Group data comes from both a unique API call and from the initial call
     * of getting the board data, so this data is merged; this boolean is to avoid fetching this data twice.
     *
     * @var bool
     */
    private $groupsFetched = false;

    // ================================================================================================================
    //   Getter functions
    // ================================================================================================================

    /**
     * The resource's URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * The board's unique identifier.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The board's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The board's description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * The board's visible columns.
     *
     * @return array
     */
    public function getColumns()
    {
        self::lazyInject($this->columns, array(
            "board_id" => $this->getId()
        ));
        self::lazyArray($this->columns, "PulseColumn");

        return $this->columns;
    }

    /**
     * Creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        self::lazyLoad($this->created_at, "DateTime");

        return $this->created_at;
    }

    /**
     * Last update time.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        self::lazyLoad($this->updated_at, "DateTime");

        return $this->updated_at;
    }

    // ================================================================================================================
    //   Columns functions
    // ================================================================================================================

    public function createColumn ($title, $type, $labels = array())
    {
        $url        = sprintf("%s/%d/columns.json", parent::apiEndpoint(), $this->getId());
        $postParams = array(
            "title" => $title,
            "type"  => $type
        );

        self::setIfNotNullOrEmpty($postParams, "labels", $labels);

        $this->jsonResponse = self::sendPost($url, $postParams);
        $this->assignResults();

        return $this;
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
            $this->groupsFetched = true;
        }

        self::lazyArray($this->groups, "PulseGroup");

        return $this->groups;
    }

    public function createGroup ($title)
    {
        $url        = sprintf("%s/%s/groups.json", parent::apiEndpoint(), $this->getId());
        $postParams = array("title" => $title);

        // The API doesn't return the board ID, so since we have access to it here: set it manually
        $groupResult             = self::sendPost($url, $postParams);
        $groupResult["board_id"] = $this->id;

        return (new PulseGroup($groupResult));
    }

    private function fetchGroups ($showArchived)
    {
        $url = sprintf("%s/%s/groups.json", parent::apiEndpoint(), $this->getId());

        $this->groupsFetched = true;

        return self::sendGet($url, array("show_archived" => $showArchived));
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

    public static function createBoard ($user_id, $name, $description = NULL)
    {
        $url        = sprintf("%s.json", parent::apiEndpoint());
        $postParams = array(
            "user_id" => $user_id,
            "name"    => $name
        );

        self::setIfNotNullOrEmpty($postParams, "description", $description);

        $boardResult = self::sendPost($url, $postParams);

        return (new PulseBoard($boardResult));
    }

    public static function getBoards ($params = array())
    {
        $url = sprintf("%s.json", parent::apiEndpoint());

        return parent::fetchJsonArrayToObjectArray($url, "PulseBoard", $params);
    }
}