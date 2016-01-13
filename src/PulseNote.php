<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Objects\ApiObject;

class PulseNote extends ApiObject
{
    const API_PREFIX = "pulses";

    // ================================================================================================================
    //   Instance Variables
    // ================================================================================================================

    /**
     * The collaboration box type (rich_text, file_list, faq_list).
     *
     * @var string
     */
    protected $type;

    /**
     * The note's id.
     *
     * @var string
     */
    protected $id;

    /**
     * The note's title.
     *
     * @var string
     */
    protected $title;

    /**
     * The note's project_id.
     *
     * @var string
     */
    protected $project_id;

    /**
     * Describes who can edit this note. Can be either 'everyone' or 'owners'.
     *
     * @var string
     */
    protected $permissions;

    /**
     * The note's body
     *
     * @var string
     */
    protected $content;

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

    public function __construct ($array)
    {
        $this->arrayConstructionOnly = true;

        parent::__construct($array);
    }

    // ================================================================================================================
    //   Getter functions
    // ================================================================================================================

    /**
     * The collaboration box type (rich_text, file_list, faq_list).
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * The note's id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The note's title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * The note's project_id.
     *
     * @return string
     */
    public function getPulseId()
    {
        return $this->project_id;
    }

    /**
     * Describes who can edit this note. Can be either 'everyone' or 'owners'.
     *
     * @return string
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * The note's body.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        self::lazyLoad($this->created_at, '\DateTime');

        return $this->created_at;
    }

    /**
     * Last update time.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        self::lazyLoad($this->updated_at, '\DateTime');

        return $this->updated_at;
    }

    // ================================================================================================================
    //   Modification functions
    // ================================================================================================================

    /**
     * Edit a note's content or information. Set values to NULL in order to not update them
     *
     * @api
     *
     * @param  null|string        $title         The new title of the note
     * @param  null|string        $content       The new content of the note
     * @param  null|int|PulseUser $user_id       The new author of the note
     * @param  null|bool          $create_update Whether to create an update or not
     *
     * @since  0.1.0
     *
     * @throws InvalidObjectException    The object has already been deleted from DaPulse
     * @throws \InvalidArgumentException An update was to be created but no author for the update was specified
     *
     * @return $this
     */
    public function editNote ($title = NULL, $content = NULL, $user_id = NULL, $create_update = NULL)
    {
        $this->checkInvalid();

        if ($user_id instanceof PulseUser)
        {
            $user_id = $user_id->getId();
        }

        $url        = $this->getNotesUrl();
        $postParams = array(
            "id"      => $this->getPulseId(),
            "note_id" => $this->getId()
        );

        self::setIfNotNullOrEmpty($postParams, "title", $title);
        self::setIfNotNullOrEmpty($postParams, "content", $content);
        self::setIfNotNullOrEmpty($postParams, "user_id", $user_id);
        self::setIfNotNullOrEmpty($postParams, "create_update", $create_update);

        if ($create_update && is_null($user_id))
        {
            throw new \InvalidArgumentException("The user_id value must be set if an update is to be created");
        }

        $this->jsonResponse = self::sendPut($url, $postParams);
        $this->assignResults();

        return $this;
    }

    /**
     * Edit the title of the note only.
     *
     * **Note** This is a convenience function that just calls PulseNote->editNote(). In order to change multiple
     * values, use PulseNote->editNote() instead of chaining the individual edit functions because each convenience
     * function will make their own separate API call making the process significantly slower.
     *
     * @api
     *
     * @param  string $title The new title of the note
     *
     * @since  0.1.0
     *
     * @return $this
     */
    public function editTitle ($title)
    {
        return $this->editNote($title);
    }

    /**
     * Edit the content of the note only.
     *
     * **Note** This is a convenience function that just calls PulseNote->editNote(). In order to change multiple
     * values, use PulseNote->editNote() instead of chaining the individual edit functions because each convenience
     * function will make their own separate API call making the process significantly slower.
     *
     * @api
     *
     * @param  string $content The new content of the note
     *
     * @since  0.1.0
     *
     * @return $this
     */
    public function editContent ($content)
    {
        return $this->editNote(NULL, $content);
    }

    /**
     * Edit the author of the note only.
     *
     * **Note** This is a convenience function that just calls PulseNote->editNote(). In order to change multiple
     * values, use PulseNote->editNote() instead of chaining the individual edit functions because each convenience
     * function will make their own separate API call making the process significantly slower.
     *
     * @api
     *
     * @param  string $user_id The new author of the note
     *
     * @since  0.1.0
     *
     * @return $this
     */
    public function editAuthor ($user_id)
    {
        return $this->editNote(NULL, NULL, $user_id);
    }

    /**
     * Delete this note
     *
     * @api
     *
     * @since 0.1.0
     *
     * @throws InvalidObjectException The object has already been deleted from DaPulse
     */
    public function deleteNote ()
    {
        $this->checkInvalid();

        self::sendDelete($this->getNotesUrl());

        $this->deletedObject = true;
    }

    private function getNotesUrl ()
    {
        return sprintf("%s/%s/notes/%s.json", self::apiEndpoint(), $this->getPulseId(), $this->getId());
    }
}