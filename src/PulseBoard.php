<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Exceptions\ArgumentMismatchException;
use allejo\DaPulse\Exceptions\InvalidArraySizeException;
use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Objects\SubscribableObject;
use allejo\DaPulse\Utilities\StringUtilities;

/**
 * This class contains all of the respective functionality for working a board on DaPulse
 *
 * @api
 * @package allejo\DaPulse
 * @since   0.1.0
 */
class PulseBoard extends SubscribableObject
{
    /**
     * The suffix that is appended to the URL to access functionality for certain objects
     *
     * @internal
     */
    const API_PREFIX = "boards";

    // =================================================================================================================
    //   Instance Variables
    // =================================================================================================================

    /**
     * The resource's URL.
     *
     * @var string
     */
    protected $url;

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

    // =================================================================================================================
    //   Getter functions
    // =================================================================================================================

    /**
     * The resource's URL.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getUrl ()
    {
        $this->lazyLoad();

        return $this->url;
    }

    /**
     * The board's unique identifier.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return int
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * The board's name.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getName ()
    {
        $this->lazyLoad();

        return $this->name;
    }

    /**
     * The board's description.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getDescription ()
    {
        $this->lazyLoad();

        return $this->description;
    }

    /**
     * Creation time.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return \DateTime
     */
    public function getCreatedAt ()
    {
        $this->lazyLoad();
        self::lazyCast($this->created_at, '\DateTime');

        return $this->created_at;
    }

    /**
     * Last update time.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return \DateTime
     */
    public function getUpdatedAt ()
    {
        $this->lazyLoad();
        self::lazyCast($this->updated_at, '\DateTime');

        return $this->updated_at;
    }

    // =================================================================================================================
    //   Columns functions
    // =================================================================================================================

    /**
     * The board's visible columns.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return PulseColumn[]
     */
    public function getColumns ()
    {
        $this->lazyLoad();

        self::lazyInject($this->columns, [
            "board_id" => $this->getId()
        ]);
        self::lazyCastAll($this->columns, "PulseColumn");

        return $this->columns;
    }

    /**
     * Create a new column for the current board.
     *
     * If you are creating a status column, use the constants available in the **PulseColumnColorValue** class to match
     * the colors. Keep in mind this array cannot have a key higher than 11 nor can it be an associative array. Here's
     * an example of how to match statuses with specific colors.
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
     * @see   PulseColumn::Date    PulseColumn::Date
     * @see   PulseColumn::Person  PulseColumn::Person
     * @see   PulseColumn::Numeric PulseColumn::Numeric
     * @see   PulseColumn::Status  PulseColumn::Status
     * @see   PulseColumn::Text    PulseColumn::Text
     * @see   PulseColumnStatusValue::Orange  PulseColumnStatusValue::Orange
     * @see   PulseColumnStatusValue::L_Green PulseColumnStatusValue::L_Green
     * @see   PulseColumnStatusValue::Red     PulseColumnStatusValue::Red
     * @see   PulseColumnStatusValue::Blue    PulseColumnStatusValue::Blue
     * @see   PulseColumnStatusValue::Purple  PulseColumnStatusValue::Purple
     * @see   PulseColumnStatusValue::Grey    PulseColumnStatusValue::Grey
     * @see   PulseColumnStatusValue::Green   PulseColumnStatusValue::Green
     * @see   PulseColumnStatusValue::L_Blue  PulseColumnStatusValue::L_Blue
     * @see   PulseColumnStatusValue::Gold    PulseColumnStatusValue::Gold
     * @see   PulseColumnStatusValue::Yellow  PulseColumnStatusValue::Yellow
     * @see   PulseColumnStatusValue::Black   PulseColumnStatusValue::Black
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
    public function createColumn ($title, $type, $labels = [])
    {
        if ($type !== PulseColumn::Status && !empty($labels))
        {
            throw new ArgumentMismatchException("No color definitions are required for a non-color column.");
        }

        if ($type === PulseColumn::Status && count($labels) > 0 && max(array_keys($labels)) > 10)
        {
            throw new InvalidArraySizeException("The range of status can only be from 0-10.");
        }

        $url        = sprintf("%s/%d/columns.json", self::apiEndpoint(), $this->getId());
        $postParams = [
            "title" => $title,
            "type"  => $type
        ];

        self::setIfNotNullOrEmpty($postParams, "labels", $labels);

        $this->jsonResponse = self::sendPost($url, $postParams);
        $this->assignResults();

        return $this;
    }

    // =================================================================================================================
    //   Group functions
    // =================================================================================================================

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
        $url    = sprintf("%s/%d/groups.json", self::apiEndpoint(), $this->getId());
        $params = [
            'show_archived' => StringUtilities::booleanLiteral($showArchived)
        ];
        $result = self::sendGet($url, $params);

        self::lazyInject($result, [
            'board_id' => $this->getId()
        ]);
        self::lazyCastAll($result, 'PulseGroup');

        return $result;
    }

    /**
     * Create a new group in a board
     *
     * @api
     *
     * @param  string $title The title of the board
     *
     * @since  0.1.0
     *
     * @return PulseGroup
     */
    public function createGroup ($title)
    {
        $url        = sprintf("%s/%s/groups.json", self::apiEndpoint(), $this->getId());
        $postParams = ["title" => $title];

        // The API doesn't return the board ID, so since we have access to it here: set it manually
        $groupResult             = self::sendPost($url, $postParams);
        $groupResult["board_id"] = $this->getId();

        return (new PulseGroup($groupResult));
    }

    /**
     * Delete a group from a board
     *
     * @api
     *
     * @param string $groupId The group ID to be deleted
     *
     * @since 0.3.0 An array of PulseGroup objects representing the current groups in this board and their states
     * @since 0.1.0
     *
     * @return PulseGroup[]
     */
    public function deleteGroup ($groupId)
    {
        $url    = sprintf("%s/%d/groups/%s.json", self::apiEndpoint(), $this->getId(), $groupId);
        $result = self::sendDelete($url);

        self::lazyInject($result, [
            'board_id' => $this->getId()
        ]);
        self::lazyCastAll($result, 'PulseGroup');

        return $result;
    }

    // =================================================================================================================
    //   Pulse functions
    // =================================================================================================================

    /**
     * Get all of the Pulses belonging to this board
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return Pulse[]
     */
    public function getPulses ()
    {
        $url    = sprintf("%s/%d/pulses.json", self::apiEndpoint(), $this->getId());
        $data   = self::sendGet($url);
        $pulses = [];

        foreach ($data as $entry)
        {
            $this->pulseInjection($entry);

            $pulses[] = new Pulse($entry["pulse"]);
        }

        return $pulses;
    }

    /**
     * Create a new Pulse inside of this board
     *
     * Using the $updateText and $announceToAll parameters is the equivalent of using Pulse::createUpdate() after a
     * Pulse has been created but with one less API call.
     *
     * @api
     *
     * @param string        $name          The name of the Pulse
     * @param PulseUser|int $user          The owner of the Pulse, i.e. who created it
     * @param string|null   $groupId       The group to add this Pulse to
     * @param string|null   $updateText    The update's text, can contain simple HTML for formatting
     * @param bool|null     $announceToAll Determines if the update should be sent to everyone's wall
     *
     * @throws \InvalidArgumentException if $user is not a valid user by definition
     *
     * @since 0.3.0 An \InvalidArgumentException may be thrown
     * @since 0.1.0
     *
     * @return Pulse
     */
    public function createPulse ($name, $user, $groupId = null, $updateText = null, $announceToAll = null)
    {
        $user       = PulseUser::_castToInt($user);
        $url        = sprintf("%s/%d/pulses.json", self::apiEndpoint(), $this->getId());
        $postParams = [
            "user_id" => $user,
            "pulse"   => [
                "name" => $name
            ]
        ];

        self::setIfNotNullOrEmpty($postParams, "group_id", $groupId);
        self::setIfNotNullOrEmpty($postParams['update'], 'text', $updateText);
        self::setIfNotNullOrEmpty($postParams['update'], 'announcement', $announceToAll);

        $result = self::sendPost($url, $postParams);
        $this->pulseInjection($result);

        return (new Pulse($result["pulse"]));
    }

    private function pulseInjection (&$result)
    {
        // Inject some information so a Pulse object can survive on its own
        $result["pulse"]["group_id"]          = $result["board_meta"]["group_id"];
        $result["pulse"]["column_structure"]  = $this->getColumns();
        $result["pulse"]["raw_column_values"] = $result["column_values"];
    }

    // =================================================================================================================
    //   Board functions
    // =================================================================================================================

    /**
     * Archive this board
     *
     * @api
     *
     * @since 0.1.0
     *
     * @throws InvalidObjectException if the object has already been deleted
     */
    public function archiveBoard ()
    {
        $this->checkInvalid();

        $url = sprintf("%s/%s.json", self::apiEndpoint(), $this->getId());
        self::sendDelete($url);

        $this->deletedObject = true;
    }

    /**
     * Create a new board
     *
     * @api
     *
     * @param  string        $name        The name of the board
     * @param  int|PulseUser $user        The owner of the board
     * @param  string|null   $description A description of the board
     *
     * @since  0.3.0 $userId may be a PulseUser object and \InvalidArgumentException is now thrown
     * @since  0.1.0
     *
     * @throws \InvalidArgumentException if $user is not a valid user by definition
     *
     * @return PulseBoard
     */
    public static function createBoard ($name, $user, $description = null)
    {
        $user       = PulseUser::_castToInt($user);
        $url        = sprintf("%s.json", self::apiEndpoint());
        $postParams = [
            "user_id" => $user,
            "name"    => $name
        ];

        self::setIfNotNullOrEmpty($postParams, "description", $description);

        $boardResult = self::sendPost($url, $postParams);

        return (new PulseBoard($boardResult));
    }

    /**
     * Get all the account's boards
     *
     * ```
     * array['page']            int  - Page offset to fetch
     *      ['per_page']        int  - Number of results to return per page
     *      ['offset']          int  - Pad a number of results
     *      ['only_globals']    bool - Return only global boards
     *      ['order_by_latest'] bool - Order by newest boards
     * ```
     *
     * @api
     *
     * @param  array $params Parameters to filter the boards (see above)
     *
     * @since  0.1.0
     *
     * @return PulseBoard[]
     */
    public static function getBoards ($params = [])
    {
        $url = sprintf("%s.json", self::apiEndpoint());

        return self::fetchAndCastToObjectArray($url, "PulseBoard", $params);
    }
}