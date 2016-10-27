<?php

/**
 * This file contains the Pulse class
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Exceptions\ColumnNotFoundException;
use allejo\DaPulse\Exceptions\HttpException;
use allejo\DaPulse\Exceptions\InvalidColumnException;
use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Objects\PulseColumnDateValue;
use allejo\DaPulse\Objects\PulseColumnNumericValue;
use allejo\DaPulse\Objects\PulseColumnPersonValue;
use allejo\DaPulse\Objects\PulseColumnStatusValue;
use allejo\DaPulse\Objects\PulseColumnTextValue;
use allejo\DaPulse\Objects\PulseColumnValue;
use allejo\DaPulse\Objects\SubscribableObject;
use allejo\DaPulse\Utilities\ArrayUtilities;

/**
 * A class representing a single pulse in a board
 *
 * @api
 * @package allejo\DaPulse
 * @since   0.1.0
 */
class Pulse extends SubscribableObject
{
    /**
     * @ignore
     */
    const API_PREFIX = "pulses";

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
     * The pulse's name.
     *
     * @var string
     */
    protected $name;

    /**
     * The amount of updates a pulse has.
     *
     * @var int
     */
    protected $updates_count;

    /**
     * The ID of the parent board.
     *
     * @var int
     */
    protected $board_id;

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
     * The ID of the group this pulse belongs to
     *
     * @var string
     */
    protected $group_id;

    /**
     * @var PulseColumn[]
     */
    protected $column_structure;

    /**
     * An array containing all of the values a pulse has for each column
     *
     * @var mixed
     */
    protected $raw_column_values;

    /**
     * An array containing objects extended from PulseColumnValue storing all of the values for each column
     *
     * @var array
     */
    protected $column_values;

    /**
     * The common URL path for retrieving objects relating a pulse such as subscribers, notes, or updates
     *
     * @var string
     */
    private $urlSyntax = "%s/%s/%s.json";

    // ================================================================================================================
    //   Overloaded functions
    // ================================================================================================================

    protected function initializeValues ()
    {
        $this->column_values     = array();
        $this->column_structure  = array();
        $this->raw_column_values = array();
    }

    // ================================================================================================================
    //   Getter functions
    // ================================================================================================================

    /**
     * The resource's URL.
     *
     * @return string
     */
    public function getUrl ()
    {
        return $this->url;
    }

    /**
     * The pulse's name.
     *
     * @return string
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * The amount of updates a pulse has.
     *
     * @return int
     */
    public function getUpdatesCount ()
    {
        return $this->updates_count;
    }

    /**
     * The ID of the parent board.
     *
     * @return int
     */
    public function getBoardId ()
    {
        return $this->board_id;
    }

    /**
     * Creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt ()
    {
        self::lazyCast($this->created_at, '\DateTime');

        return $this->created_at;
    }

    /**
     * Last update time.
     *
     * @return \DateTime
     */
    public function getUpdatedAt ()
    {
        self::lazyCast($this->updated_at, '\DateTime');

        return $this->updated_at;
    }

    /**
     * Get the ID of the group this Pulse is a part of. If this value is not available, an API call will be made to
     * find the group ID via brute force.
     *
     * **Note** The group ID is cached if it is not available. To update the cached value, use $forceFetch to force an
     * API call to get a new value.
     *
     * **Warning** An API call is always slower than using the cached value.
     *
     * @param bool $forceFetch Force an API call to get an updated group ID if it has been changed
     *
     * @since 0.1.0
     * @return string
     */
    public function getGroupId ($forceFetch = false)
    {
        if (empty($this->group_id) || $forceFetch)
        {
            $parentBoard = new PulseBoard($this->board_id);
            $pulses      = $parentBoard->getPulses();

            foreach ($pulses as $pulse)
            {
                if ($this->getId() === $pulse->getId())
                {
                    $this->group_id = $pulse->getGroupId();
                    break;
                }
            }
        }

        return $this->group_id;
    }

    // ================================================================================================================
    //   Pulse functions
    // ================================================================================================================

    /**
     * Edit the name of the pulse
     *
     * @api
     * @param string $title
     * @since 0.1.0
     */
    public function editName($title)
    {
        $editUrl    = sprintf("%s/%d.json", self::apiEndpoint(), $this->getId());
        $postParams = array(
            'name' => $title
        );

        $this->jsonResponse = self::sendPut($editUrl, $postParams);
        $this->assignResults();
    }

    /**
     * Archive the current pulse.
     *
     * This is the equivalent of a soft delete and can be restored from the DaPulse website.
     *
     * @api
     * @since 0.1.0
     */
    public function archivePulse()
    {
        $archiveURL = sprintf("%s/%d.json", self::apiEndpoint(), $this->getId());
        $getParams  = array(
            'archive' => true
        );

        self::sendDelete($archiveURL, $getParams);
    }

    /**
     * Delete the current Pulse
     *
     * @api
     * @throws \allejo\DaPulse\Exceptions\InvalidObjectException
     */
    public function deletePulse ()
    {
        $this->checkInvalid();

        $deleteURL = sprintf("%s/%d.json", self::apiEndpoint(), $this->getId());

        self::sendDelete($deleteURL);

        $this->deletedObject = true;
    }

    public function duplicatePulse ($groupId = NULL, $ownerId = NULL)
    {
        $url        = sprintf("%s/%s/pulses/%s/duplicate.json", self::apiEndpoint("boards"), $this->getBoardId(), $this->getId());
        $postParams = array();

        if ($ownerId instanceof PulseUser)
        {
            $ownerId = $ownerId->getId();
        }

        self::setIfNotNullOrEmpty($postParams, "group_id", $groupId);
        self::setIfNotNullOrEmpty($postParams, "owner_id", $ownerId);

        $result = self::sendPost($url, $postParams);
        $this->pulseInjection($result);

        return (new Pulse($result['pulse']));
    }

    private function pulseInjection (&$result)
    {
        $parentBoard = new PulseBoard($this->getBoardId());

        // Inject some information so a Pulse object can survive on its own
        $result["pulse"]["group_id"]          = $result["board_meta"]["group_id"];
        $result["pulse"]["column_structure"]  = $parentBoard->getColumns();
        $result["pulse"]["raw_column_values"] = $result["column_values"];
    }

    // ================================================================================================================
    //   Column data functions
    // ================================================================================================================

    /**
     * Access a color type column value belonging to this pulse in order to read it or modify.
     *
     * This function should only be used to access color type values; an exception will be thrown otherwise.
     *
     * @api
     *
     * @param string $columnId The ID of the column to access. This is typically a slugified version of the column name
     *
     * @since 0.1.0
     * @throws InvalidColumnException The specified column is not a "color" type column
     * @throws InvalidObjectException The specified column exists but modification of its value is unsupported either
     *                                by this library or the DaPulse API.
     * @throws InvalidColumnException   The specified column ID does not exist for this Pulse
     * @return PulseColumnStatusValue A column object with access to its contents
     */
    public function getStatusColumn ($columnId)
    {
        return $this->getColumn($columnId, PulseColumn::Status);
    }

    /**
     * Access a date type column value belonging to this pulse in order to read it or modify.
     *
     * This function should only be used to access data type values; an exception will be thrown otherwise.
     *
     * @api
     *
     * @param  string $columnId The ID of the column to access. This is typically a slugified version of the column name
     *
     * @since  0.1.0
     *
     * @throws ColumnNotFoundException The specified column ID does not exist for this Pulse
     * @throws InvalidColumnException  The specified column is not a "date" type column
     * @throws InvalidObjectException  The specified column exists but modification of its value is unsupported either
     *                                 by this library or the DaPulse API.
     *
     * @return PulseColumnDateValue A column object with access to its contents
     */
    public function getDateColumn ($columnId)
    {
        return $this->getColumn($columnId, PulseColumn::Date);
    }

    /**
     * Access a date type column value belonging to this pulse in order to read it or modify.
     *
     * This function should only be used to access data type values; an exception will be thrown otherwise.
     *
     * @api
     *
     * @param  string $columnId The ID of the column to access. This is typically a slugified version of the column name
     *
     * @since  0.2.0
     *
     * @throws ColumnNotFoundException The specified column ID does not exist for this Pulse
     * @throws InvalidColumnException  The specified column is not a "numeric" type column
     * @throws InvalidObjectException  The specified column exists but modification of its value is unsupported either
     *                                 by this library or the DaPulse API.
     *
     * @return PulseColumnNumericValue A column object with access to its contents
     */
    public function getNumericColumn ($columnId)
    {
        return $this->getColumn($columnId, PulseColumn::Numeric);
    }

    /**
     * Access a person type column value belonging to this pulse in order to read it or modify.
     *
     * This function should only be used to access person type values; an exception will be thrown otherwise.
     *
     * @api
     *
     * @param  string $columnId The ID of the column to access. This is typically a slugified version of the column name
     *
     * @since  0.1.0
     *
     * @throws ColumnNotFoundException The specified column ID does not exist for this Pulse
     * @throws InvalidColumnException  The specified column is not a "person" type column
     * @throws InvalidObjectException  The specified column exists but modification of its value is unsupported either
     *                                 by this library or the DaPulse API.
     *
     * @return PulseColumnPersonValue A column object with access to its contents
     */
    public function getPersonColumn ($columnId)
    {
        return $this->getColumn($columnId, PulseColumn::Person);
    }

    /**
     * Access a text type column value belonging to this pulse in order to read it or modify.
     *
     * This function should only be used to access text type values; an exception will be thrown otherwise.
     *
     * @api
     *
     * @param  string $columnId The ID of the column to access. This is typically a slugified version of the column name
     *
     * @since  0.1.0

     * @throws ColumnNotFoundException The specified column ID does not exist for this Pulse
     * @throws InvalidColumnException  The specified column is not a "text" type column
     * @throws InvalidObjectException  The specified column exists but modification of its value is unsupported either
     *                                 by this library or the DaPulse API.
     *
     * @return PulseColumnTextValue A column object with access to its contents
     */
    public function getTextColumn ($columnId)
    {
        return $this->getColumn($columnId, PulseColumn::Text);
    }

    /**
     * Build a pulse's column object if it doesn't exist or return the existing column.
     *
     * @param  string $columnId   The ID of the column to access. This is typically a slugified version of the column
     *                            title
     * @param  string $columnType The type of column being accessed: 'text', 'color', 'person', 'numeric', or 'date'
     *
     * @since  0.1.0
     *
     * @throws ColumnNotFoundException The specified column ID does not exist for this Pulse
     * @throws InvalidColumnException  The specified column is not the same type as specified in `$columnType`
     * @throws InvalidObjectException  The specified column exists but modification of its value is unsupported either
     *                                 by this library or the DaPulse API.
     *
     * @return PulseColumnValue The returned object will be a child of this abstract class.
     */
    private function getColumn ($columnId, $columnType)
    {
        if (!isset($this->column_values) || !array_key_exists($columnId, $this->column_values))
        {
            $key  = ArrayUtilities::array_search_column($this->raw_column_values, 'cid', $columnId);
            $data = array();

            // We can't find the key, this means that we got our information from accessing a Pulse directly instead of
            // getting it through a PulseBoard. This isn't as robust as accessing a PulseBoard but it's more efficient.
            // We make a separate API call to get the value of a column.
            if ($key === false)
            {
                $url    = sprintf("%s/%d/columns/%s/value.json", self::apiEndpoint("boards"), $this->getBoardId(), $columnId);
                $params = array(
                    "pulse_id" => $this->getId()
                );

                try
                {
                    $results = self::sendGet($url, $params);
                }
                catch (HttpException $e)
                {
                    throw new ColumnNotFoundException("The '$columnId' column could not be found");
                }

                // Store our value inside of jsonResponse so all of the respective objects can treat the data the same
                // as when accessed through a PulseBoard
                $data['jsonResponse']['value'] = $results['value'];
            }
            else
            {
                $data = $this->raw_column_values[$key];
                $type = $this->column_structure[$key]->getType();

                if ($type !== $columnType)
                {
                    throw new InvalidColumnException("The '$columnId' column was expected to be '$columnType' but was '$type' instead.");
                }
            }

            $data['column_id'] = $columnId;
            $data['board_id']  = $this->getBoardId();
            $data['pulse_id']  = $this->getId();

            $this->column_values[$columnId] = PulseColumnValue::_createColumnType($columnType, $data);
        }

        return $this->column_values[$columnId];
    }

    // ================================================================================================================
    //   Notes functions
    // ================================================================================================================

    /**
     * Create a new note in this project
     *
     * @api
     *
     * @param  string   $title         The title of the note
     * @param  string   $content       The body of the note
     * @param  bool     $ownersOnly    Set to true if only pulse owners can edit this note.
     * @param  int|null $userId        The id of the user to be marked as the note's last updater
     * @param  bool     $createUpdate  Indicates whether to create an update on the pulse notifying subscribers on the
     *                                 changes (required user_id to be set).
     *
     * @since  0.1.0
     * @return PulseNote
     */
    public function addNote ($title, $content, $ownersOnly = false, $userId = NULL, $createUpdate = false)
    {
        $url        = sprintf($this->urlSyntax, self::apiEndpoint(), $this->id, "notes");
        $postParams = array(
            "id"            => $this->id,
            "title"         => $title,
            "content"       => $content,
            "owners_only"   => $ownersOnly,
            "create_update" => $createUpdate
        );

        self::setIfNotNullOrEmpty($postParams, "user_id", $userId);

        if ($createUpdate && is_null($userId))
        {
            throw new \InvalidArgumentException("The user_id value must be set if an update is to be created");
        }

        $noteResult = self::sendPost($url, $postParams);

        return (new PulseNote($noteResult));
    }

    /**
     * Return all of the notes belonging to this project
     *
     * @api
     * @since  0.1.0
     * @return PulseNote[]
     */
    public function getNotes ()
    {
        $url = sprintf($this->urlSyntax, self::apiEndpoint(), $this->id, "notes");

        return self::fetchAndCastToObjectArray($url, "PulseNote");
    }

    // ================================================================================================================
    //   Updates functions
    // ================================================================================================================

    /**
     * Get all of the updates that belong this Pulse in reverse chronological order
     *
     * @api
     * @since 0.1.0
     * @return PulseUpdate[]
     */
    public function getUpdates ()
    {
        $url = sprintf($this->urlSyntax, self::apiEndpoint(), $this->id, "updates");

        return self::fetchAndCastToObjectArray($url, "PulseUpdate");
    }

    /**
     * Create an update for the current Pulse
     *
     * @api
     *
     * @param int|PulseUser $user
     * @param string        $text
     * @param null|bool     $announceToAll
     *
     * @since 0.1.0
     */
    public function createUpdate ($user, $text, $announceToAll = NULL)
    {
        PulseUpdate::createUpdate($user, $this->getId(), $text, $announceToAll);
    }

    // ================================================================================================================
    //   Static functions
    // ================================================================================================================

    /**
     * Get all of the pulses that belong to the organization across all boards.
     *
     * To modify the amount of data returned with pagination, use the following values in the array to configure your
     * pagination or offsets.
     *
     * ```php
     * $params = array(
     *     "page"     => 1,          // (int) Page offset to fetch
     *     "per_page" => 10,         // (int) Number of results per page
     *     "offset"   => 5,          // (int) Instead of starting at result 0, start counting from result 5
     *     "order_by_latest" => true // (bool) Order the pulses with the most recent first
     * );
     * ```
     *
     * @api
     *
     * @param array $params GET parameters passed to with the query to modify the data returned.
     *
     * @since 0.1.0
     * @return Pulse[]
     */
    public static function getPulses ($params = array())
    {
        $url = sprintf("%s.json", self::apiEndpoint());

        return self::fetchAndCastToObjectArray($url, "Pulse", $params);
    }
}