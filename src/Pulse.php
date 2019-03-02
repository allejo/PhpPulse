<?php

/**
 * @copyright 2017 Vladimir Jimenez
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
use allejo\DaPulse\Objects\PulseColumnTagValue;
use allejo\DaPulse\Objects\PulseColumnTextValue;
use allejo\DaPulse\Objects\PulseColumnTimelineValue;
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
        $this->column_values     = [];
        $this->column_structure  = [];
        $this->raw_column_values = [];
    }

    // ================================================================================================================
    //   Getter functions
    // ================================================================================================================

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
     * The pulse's name.
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
     * The amount of updates a pulse has.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return int
     */
    public function getUpdatesCount ()
    {
        $this->lazyLoad();

        return $this->updates_count;
    }

    /**
     * The ID of the parent board.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return int
     */
    public function getBoardId ()
    {
        $this->lazyLoad();

        return $this->board_id;
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

    /**
     * Get the ID of the group this Pulse is a part of. If this value is not available, an API call will be made to
     * find the group ID via brute force.
     *
     * **Note** The group ID is cached if it is not available. To update the cached value, use $forceFetch to force an
     * API call to get a new value.
     *
     * **Warning** An API call is always slower than using the cached value.
     *
     * @api
     *
     * @param bool $forceFetch Force an API call to get an updated group ID if it has been changed
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getGroupId ($forceFetch = false)
    {
        $this->lazyLoad();

        if (empty($this->group_id) || $forceFetch)
        {
            $parentBoard = new PulseBoard($this->board_id, true);
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
     *
     * @param string $title
     *
     * @since 0.1.0
     */
    public function editName ($title)
    {
        $editUrl    = sprintf("%s/%d.json", self::apiEndpoint(), $this->getId());
        $postParams = [
            'name' => $title
        ];

        $this->jsonResponse = self::sendPut($editUrl, $postParams);
        $this->assignResults();
    }

    /**
     * Archive the current pulse
     *
     * This is the equivalent of a soft delete and can be restored from the DaPulse website.
     *
     * @api
     *
     * @since 0.1.0
     */
    public function archivePulse ()
    {
        $archiveURL = sprintf("%s/%d.json", self::apiEndpoint(), $this->getId());
        $getParams  = [
            'archive' => true
        ];

        $this->jsonResponse = self::sendDelete($archiveURL, $getParams);
        $this->assignResults();
    }

    /**
     * Delete the current Pulse
     *
     * @api
     *
     * @since 0.1.0
     *
     * @throws InvalidObjectException
     */
    public function deletePulse ()
    {
        $this->checkInvalid();

        $deleteURL          = sprintf("%s/%d.json", self::apiEndpoint(), $this->getId());
        $this->jsonResponse = self::sendDelete($deleteURL);
        $this->assignResults();

        $this->deletedObject = true;
    }

    public function duplicatePulse ($groupId = null, $ownerId = null)
    {
        $url        = sprintf("%s/%s/pulses/%s/duplicate.json", self::apiEndpoint("boards"), $this->getBoardId(), $this->getId());
        $postParams = [];

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
     * @param  string $columnId The ID of the column to access. This is typically a slugified version of the column name
     *
     * @since  0.4.0  ColumnNotFoundException will no longer thrown, instead it'll be thrown when getValue() is called
     * @since  0.1.0
     *
     * @throws InvalidColumnException  The specified column is not a "color" type column
     * @throws InvalidObjectException  The specified column exists but modification of its value is unsupported either
     *                                 by this library or the DaPulse API.
     *
     * @return PulseColumnStatusValue A column object with access to its contents
     */
    public function getStatusColumn ($columnId)
    {
        return $this->getColumn($columnId, PulseColumn::Status);
    }

    /**
     * Access a date type column value belonging to this pulse in order to read it or modify.
     *
     * This function should only be used to access date type values; an exception will be thrown otherwise.
     *
     * @api
     *
     * @param  string $columnId The ID of the column to access. This is typically a slugified version of the column name
     *
     * @since  0.4.0  ColumnNotFoundException will no longer thrown, instead it'll be thrown when getValue() is called
     * @since  0.1.0
     *
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
     * Access a numeric type column value belonging to this pulse in order to read it or modify.
     *
     * This function should only be used to access number type values; an exception will be thrown otherwise.
     *
     * @api
     *
     * @param  string $columnId The ID of the column to access. This is typically a slugified version of the column name
     *
     * @since  0.4.0  ColumnNotFoundException will no longer thrown, instead it'll be thrown when getValue() is called
     * @since  0.2.0
     *
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
     * @since  0.4.0  ColumnNotFoundException will no longer thrown, instead it'll be thrown when getValue() is called
     * @since  0.1.0
     *
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
     * Access a tag type column value belonging to this pulse in order to read it or modify.
     *
     * This function should only be used to access text type values; an exception will be thrown otherwise.
     *
     * @api
     *
     * @param  string $columnId The ID of the column to access. This is typically a slugified version of the column name
     *
     * @since  0.3.4
     * @throws ColumnNotFoundException The specified column ID does not exist for this Pulse
     * @throws InvalidColumnException  The specified column is not a "text" type column
     * @throws InvalidObjectException  The specified column exists but modification of its value is unsupported either
     *                                 by this library or the DaPulse API.
     *
     * @return PulseColumnTagValue A column object with access to its contents
     */
    public function getTagColumn ($columnId)
    {
        return $this->getColumn($columnId, PulseColumn::Tag);
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
     * @since  0.4.0  ColumnNotFoundException will no longer thrown, instead it'll be thrown when getValue() is called
     * @since  0.1.0
     *
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
     * Access a timeline type column value belonging to this pulse in order to read it or modify.
     *
     * This function should only be used to access timeline type values; an exception will be thrown otherwise.
     *
     * @api
     *
     * @param  string $columnId The ID of the column to access. This is typically a slugified version of the column name
     *
     * @since  0.2.1
     *
     * @throws ColumnNotFoundException The specified column ID does not exist for this Pulse
     * @throws InvalidColumnException  The specified column is not a "numeric" type column
     * @throws InvalidObjectException  The specified column exists but modification of its value is unsupported either
     *                                 by this library or the DaPulse API.
     *
     * @return PulseColumnTimelineValue A column object with access to its contents
     */
    public function getTimelineColumn ($columnId)
    {
        return $this->getColumn($columnId, PulseColumn::Timeline);
    }

    /**
     * Access a column belonging to this pulse in order to read it or modify it.
     *
     * @api
     *
     * @param  string $columnId   The ID of the column to access. This is typically a slugified version of the column
     *                            title
     * @param  string $columnType The type of column being accessed. **Only** use available constants or PulseColumn::getType()
     *
     * @see    PulseColumn::Date
     * @see    PulseColumn::Numeric
     * @see    PulseColumn::Person
     * @see    PulseColumn::Status
     * @see    PulseColumn::Text
     * @see    PulseColumn::Timeline
     * @see    PulseColumnDateValue
     * @see    PulseColumnNumericValue
     * @see    PulseColumnPersonValue
     * @see    PulseColumnStatusValue
     * @see    PulseColumnTextValue
     * @see    PulseColumnTimelineValue
     *
     * @since  0.3.1
     *
     * @throws ColumnNotFoundException The specified column could not be found
     * @throws InvalidColumnException  The specified column is not the same type as specified in `$columnType`
     * @throws InvalidObjectException  The specified column exists but modification of its value is unsupported either
     *                                 by this library or the DaPulse API.
     *
     * @return mixed Returns an instance of an object extending the PulseColumnValue class
     */
    public function getColumn ($columnId, $columnType)
    {
        if (!isset($this->column_values) || !array_key_exists($columnId, $this->column_values))
        {
            $key  = ArrayUtilities::array_search_column($this->raw_column_values, 'cid', $columnId);
            $data = [];

            // We can't find the key, this means that we got our information from accessing a Pulse directly instead of
            // getting it through a PulseBoard. This isn't as robust as accessing a PulseBoard but it's more efficient.
            // We make a separate API call to get the value of a column.
            if ($key !== false)
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
     * @param  int|null $user          The id of the user to be marked as the note's last updater
     * @param  bool     $createUpdate  Indicates whether to create an update on the pulse notifying subscribers on the
     *                                 changes (required user_id to be set).
     *
     * @throws \InvalidArgumentException if $createUpdate is true and $user is null or $user is not a valid user ID or
     *                                   PulseUser object
     *
     * @since  0.1.0
     *
     * @return PulseNote
     */
    public function addNote ($title, $content, $ownersOnly = false, $user = null, $createUpdate = false)
    {
        $url        = sprintf($this->urlSyntax, self::apiEndpoint(), $this->id, "notes");
        $postParams = [
            "id"            => $this->id,
            "title"         => $title,
            "content"       => $content,
            "owners_only"   => $ownersOnly,
            "create_update" => $createUpdate
        ];

        if (!is_null($user))
        {
            $user = PulseUser::_castToInt($user);
        }

        self::setIfNotNullOrEmpty($postParams, 'user_id', $user);

        if ($createUpdate && is_null($user))
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
     *
     * @since  0.1.0
     *
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
     * Get all of the updates that belong to this Pulse in reverse chronological order
     *
     * @api
     *
     * @since 0.1.0
     *
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
     * @param  int|PulseUser $user
     * @param  string        $text
     * @param  null|bool     $announceToAll
     *
     * @since  0.3.0 A PulseUpdate object is returned containing the information of the newly created Update
     * @since  0.1.0
     *
     * @return PulseUpdate
     */
    public function createUpdate ($user, $text, $announceToAll = null)
    {
        return PulseUpdate::createUpdate($user, $this->getId(), $text, $announceToAll);
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
     *
     * @return Pulse[]
     */
    public static function getPulses ($params = [])
    {
        $url = sprintf("%s.json", self::apiEndpoint());

        return self::fetchAndCastToObjectArray($url, "Pulse", $params);
    }
}
