<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiPulse;

class PulseProject extends ApiPulse
{
    const API_PREFIX = "pulses";

    private $urlSyntax = "%s/%s/%s.json";

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

        return parent::fetchJsonArrayToObjectArray($url, "PulseProject", $params);
    }
}