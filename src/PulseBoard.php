<?php

/**
 * This file contains the PulseBoard class
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Exceptions\ArgumentMismatchException;
use allejo\DaPulse\Exceptions\InvalidArraySizeException;
use allejo\DaPulse\Objects\ApiObject;
use allejo\DaPulse\Utilities\ArrayUtilities;

/**
 * This class contains all of the respective functionality for working a board on DaPulse
 *
 * @api
 * @package allejo\DaPulse
 * @since   0.1.0
 */
class PulseBoard extends ApiObject
{
    /**
     * The suffix that is appended to the URL to access functionality for certain objects
     *
     * @internal
     */
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
     * Stores all of the pulses that belong under this board
     *
     * @var Pulse[]
     */
    protected $pulses;

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
     * @api
     * @since  0.1.0
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * The board's unique identifier.
     *
     * @api
     * @since  0.1.0
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The board's name.
     *
     * @api
     * @since  0.1.0
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The board's description.
     *
     * @api
     * @since  0.1.0
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * The board's visible columns.
     *
     * @api
     * @since  0.1.0
     * @return PulseColumn[]
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
     * @api
     * @since  0.1.0
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
     * @api
     * @since  0.1.0
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

    /**
     * Create a new column for the current board.
     *
     * If you are creating a status column, use the constants available in the **PulseColumnColorValue** class to match the
     * colors. Keep in mind this array cannot have a key higher than 11 nor can it be an associative array. Here's an
     * example of how to match statuses with specific colors.
     *
     * ```php
     * $labels = array(
     *     PulseColumnColorValue::Orange  => "Working on it",
     *     PulseColumnColorValue::L_Green => "Done",
     *     PulseColumnColorValue::Red     => "Delayed"
     * );
     * ```
     *
     * @api
     *
     * @param string $title  The title of the column. This title will automatically be "slugified" and become the ID
     *                       of the column.
     * @param string $type   The type of value that this column will use. Either use the available constants in the
     *                       PulseColumn class or use the following strings: "date", "person", "status", "text".
     * @param array  $labels If the column type will be "status," then this array will be the values for each of the
     *                       colors.
     *
     * @see PulseColumn::Date   PulseColumn::Date
     * @see PulseColumn::Person PulseColumn::Person
     * @see PulseColumn::Status PulseColumn::Status
     * @see PulseColumn::Text   PulseColumn::Text
     * @see PulseColumnColorValue::Orange  PulseColumnColorValue::Orange
     * @see PulseColumnColorValue::L_Green PulseColumnColorValue::L_Green
     * @see PulseColumnColorValue::Red     PulseColumnColorValue::Red
     * @see PulseColumnColorValue::Blue    PulseColumnColorValue::Blue
     * @see PulseColumnColorValue::Purple  PulseColumnColorValue::Purple
     * @see PulseColumnColorValue::Grey    PulseColumnColorValue::Grey
     * @see PulseColumnColorValue::Green   PulseColumnColorValue::Green
     * @see PulseColumnColorValue::L_Blue  PulseColumnColorValue::L_Blue
     * @see PulseColumnColorValue::Gold    PulseColumnColorValue::Gold
     * @see PulseColumnColorValue::Yellow  PulseColumnColorValue::Yellow
     * @see PulseColumnColorValue::Black   PulseColumnColorValue::Black
     *
     * @since 0.1.0
     *
     * @throws ArgumentMismatchException Status definitions were defined yet the type of the column was not a status
     *                                   type column
     * @throws InvalidArraySizeException The array containing the value of statuses has a key larger than the
     *                                   supported 10 indices
     *
     * @return $this This instance will be updated to have updated information to reflect the new column that was
     *               created
     */
    public function createColumn ($title, $type, $labels = array())
    {
        if ($type !== PulseColumn::Status && !empty($labels))
        {
            throw new ArgumentMismatchException("No color definitions are required for a non-color column.");
        }

        if ($type === PulseColumn::Status && count($labels) > 0 && max(array_keys($labels)) > 10)
        {
            throw new InvalidArraySizeException("The range of status can only be from 0-10.");
        }

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

    /**
     * Get all of the groups belonging to a board.
     *
     * A group is defined as the colorful headers that split up pulses into categories.
     *
     * @api
     *
     * @param bool $showArchived Set to true if you would like to get archived groups in a board as well
     *
     * @since 0.1.0
     *
     * @return PulseGroup[]
     */
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
    //   Pulse functions
    // ================================================================================================================

    public function getPulses ($forceFetch = false)
    {
        if (empty($this->pulses) || $forceFetch)
        {
            $url = sprintf("%s/%d/pulses.json", parent::apiEndpoint(), $this->getId());
            $data = self::sendGet($url);
            $this->pulses = array();

            foreach ($data as $entry)
            {
                $this->pulseInjection($entry);

                $this->pulses[] = new Pulse($entry["pulse"]);
            }
        }

        return $this->pulses;
    }

    public function createPulse ($name, $owner, $group_id = null)
    {
        $url = sprintf("%s/%d/pulses.json", parent::apiEndpoint(), $this->getId());
        $postParams = array(
            "user_id" => $owner,
            "pulse" => array(
                "name" => $name
            )
        );

        self::setIfNotNullOrEmpty($postParams, "group_id", $group_id);

        $result = self::sendPost($url, $postParams);
        $this->pulseInjection($result);

        return (new Pulse($result["pulse"]));
    }

    private function pulseInjection (&$result)
    {
        // Inject some information so a Pulse object can survive on its own
        $result["pulse"]["group_id"] = $result["board_meta"]["group_id"];
        $result["pulse"]["column_structure"] = $this->getColumns();
        $result["pulse"]["raw_column_values"] = $result["column_values"];
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

    public static function createBoard ($name, $user_id, $description = NULL)
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