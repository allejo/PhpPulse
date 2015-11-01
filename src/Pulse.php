<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiObject;
use allejo\DaPulse\Objects\PulseColumnValue;
use allejo\DaPulse\Utilities\ArrayUtilities;

class Pulse extends ApiObject
{
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
     * The pulse's unique identifier.
     *
     * @var int
     */
    protected $id;

    /**
     * The pulse's name.
     *
     * @var string
     */
    protected $name;

    /**
     * The board's subscribers.
     *
     * @var PulseUser[]
     */
    protected $subscribers;

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
     * @var array
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

    private $urlSyntax = "%s/%s/%s.json";

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
     * The pulse's unique identifier.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The pulse's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The amount of updates a pulse has.
     *
     * @return int
     */
    public function getUpdatesCount()
    {
        return $this->updates_count;
    }

    /**
     * The ID of the parent board.
     *
     * @return int
     */
    public function getBoardId()
    {
        return $this->board_id;
    }

    /**
     * Creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Last update time.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    // ================================================================================================================
    //   Column data functions
    // ================================================================================================================

    public function getColumnValue($columnId)
    {
        if (!isset($this->column_values) || !array_key_exists($columnId, $this->column_values))
        {
            $key = ArrayUtilities::array_search_column($this->raw_column_values, 'cid', $columnId);

            $data = $this->raw_column_values[$key];
            $type = $this->column_structure[$key]->getType();

            $data['column_id'] = $data['cid'];
            $data['board_id'] = $this->getBoardId();
            $data['pulse_id'] = $this->getId();

            $this->column_values[$columnId] = PulseColumnValue::createColumnType($type, $data);
        }

        return $this->column_values[$columnId];
    }

    // ================================================================================================================
    //   Subscribers functions
    // ================================================================================================================

    public function getSubscribers ($params = array())
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "subscribers");

        return parent::fetchJsonArrayToObjectArray($url, "PulseUser", $params);
    }

    // ================================================================================================================
    //   Notes functions
    // ================================================================================================================

    /**
     * Create a new note in this project
     *
     * @param  string   $title         The title of the note
     * @param  string   $content       The body of the note
     * @param  bool     $owners_only   Set to true if only pulse owners can edit this note.
     * @param  int|null $user_id       The id of the user to be marked as the note’s last updater
     * @param  bool     $create_update Indicates whether to create an update on the pulse notifying subscribers on the
     *                                 changes (required user_id to be set).
     *
     * @since  0.1.0
     *
     * @return PulseNote
     */
    public function addNote ($title, $content, $owners_only = false, $user_id = NULL, $create_update = false)
    {
        $url        = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "notes");
        $postParams = array(
            "id"            => $this->id,
            "title"         => $title,
            "content"       => $content,
            "owners_only"   => $owners_only,
            "create_update" => $create_update
        );

        self::setIfNotNullOrEmpty($postParams, "user_id", $user_id);

        if ($create_update && is_null($user_id))
        {
            throw new \InvalidArgumentException("The user_id value must be set if an update is to be created");
        }

        $noteResult = self::sendPost($url, $postParams);

        return (new PulseNote($noteResult));
    }

    /**
     * Return all of the notes belonging to this project
     *
     * @since  0.1.0
     *
     * @return PulseNote[]
     */
    public function getNotes ()
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "notes");

        return parent::fetchJsonArrayToObjectArray($url, "PulseNote");
    }

    // ================================================================================================================
    //   Updates functions
    // ================================================================================================================

    public function getUpdates ()
    {
        $url = sprintf($this->urlSyntax, parent::apiEndpoint(), $this->id, "updates");

        return parent::fetchJsonArrayToObjectArray($url, "PulseUpdate");
    }

    // ================================================================================================================
    //   Static functions
    // ================================================================================================================

    public static function getPulses ($params = array())
    {
        $url = sprintf("%s.json", parent::apiEndpoint());

        return parent::fetchJsonArrayToObjectArray($url, "Pulse", $params);
    }
}