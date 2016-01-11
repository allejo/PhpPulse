<?php

namespace allejo\DaPulse;

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
    public function getProjectId()
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

    public function editNote ($title = NULL, $content = NULL, $user_id = NULL, $create_update = NULL)
    {
        $this->checkInvalid();

        $url        = $this->getNotesUrl();
        $postParams = array(
            "id"      => $this->getProjectId(),
            "note_id" => $this->getId()
        );

        self::setIfNotNullOrEmpty($postParams, "title", $title);
        self::setIfNotNullOrEmpty($postParams, "content", $content);
        self::setIfNotNullOrEmpty($postParams, "user_id", $user_id);
        self::setIfNotNullOrEmpty($postParams, "create_update", $create_update);

        $this->jsonResponse = self::sendPut($url, $postParams);
        $this->assignResults();

        return $this;
    }

    public function deleteNote ()
    {
        $this->checkInvalid();

        self::sendDelete($this->getNotesUrl());

        $this->deletedObject = true;
    }

    private function getNotesUrl ()
    {
        return sprintf("%s/%s/notes/%s.json", self::apiEndpoint(), $this->getProjectId(), $this->getId());
    }
}