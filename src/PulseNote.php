<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\Objects\ApiObject;

/**
 * Class PulseNote
 *
 * @api
 * @package allejo\DaPulse
 * @since   0.1.0
 */
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
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getType ()
    {
        return $this->type;
    }

    /**
     * The note's id.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * The note's title.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getTitle ()
    {
        return $this->title;
    }

    /**
     * The note's project_id.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getPulseId ()
    {
        return $this->project_id;
    }

    /**
     * Describes who can edit this note. Can be either 'everyone' or 'owners'.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getPermissions ()
    {
        return $this->permissions;
    }

    /**
     * The note's body.
     *
     * @api
     *
     * @since  0.1.0
     *
     * @return string
     */
    public function getContent ()
    {
        return $this->content;
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
        self::lazyCast($this->updated_at, '\DateTime');

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
     * @param  null|string        $title        The new title of the note
     * @param  null|string        $content      The new content of the note
     * @param  null|int|PulseUser $user         The new author of the note
     * @param  null|bool          $createUpdate Whether to create an update or not
     *
     * @since  0.1.0
     *
     * @throws InvalidObjectException    The object has already been deleted from DaPulse
     * @throws \InvalidArgumentException An update was to be created but no author for the update was specified
     *
     * @return $this
     */
    public function editNote ($title = null, $content = null, $user = null, $createUpdate = null)
    {
        $this->checkInvalid();

        if (!is_null($user))
        {
            $user = PulseUser::_castToInt($user);
        }

        $url        = $this->getNotesUrl();
        $postParams = [
            "id"      => $this->getPulseId(),
            "note_id" => $this->getId()
        ];

        self::setIfNotNullOrEmpty($postParams, "title", $title);
        self::setIfNotNullOrEmpty($postParams, "content", $content);
        self::setIfNotNullOrEmpty($postParams, "user_id", $user);
        self::setIfNotNullOrEmpty($postParams, "create_update", $createUpdate);

        if ($createUpdate && is_null($user))
        {
            throw new \InvalidArgumentException('The $user value must be set if an update is to be created');
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
        return $this->editNote(null, $content);
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
     * @param  string $userId The new author of the note
     *
     * @since  0.1.0
     *
     * @return $this
     */
    public function editAuthor ($userId)
    {
        return $this->editNote(null, null, $userId);
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