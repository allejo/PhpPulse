<?php

/**
 * This file contains the definition of all of the Note elements returned by the API
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

/**
 * A "partial" class that contains the Note API elements and their appropriate get methods
 *
 * @since 0.1.0
 */
class ApiNote extends ApiObject
{
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
}
