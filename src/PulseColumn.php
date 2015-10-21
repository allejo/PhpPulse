<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiObject;

class PulseColumn extends ApiObject
{
    const API_PREFIX = "boards";

    const Date   = "date";
    const Person = "person";
    const Status = "status";
    const Text   = "text";

    protected $id;
    protected $title;
    protected $type;
    protected $empty_text;
    protected $labels;
    protected $board_id;

    public function getId ()
    {
        return $this->id;
    }

    public function getTitle ()
    {
        return $this->title;
    }

    public function getType ()
    {
        return $this->type;
    }

    public function getEmptyText ()
    {
        return $this->empty_text;
    }

    public function getLabels ()
    {
        return $this->labels;
    }

    public function getBoardId ()
    {
        return $this->board_id;
    }

    public function editTitle ($title)
    {
        $this->editField("title", $title);
    }

    public function editLabels ($labels)
    {
        $this->editField("labels", $labels);
    }

    public function deleteColumn ()
    {
        $this->checkInvalid();

        self::sendDelete($this->getColumnsUrl());

        $this->deletedObject = true;
    }

    private function editField ($field, $value)
    {
        $this->checkInvalid();

        $postParams = array(
            $field => $value
        );

        self::sendPut($this->getColumnsUrl(), $postParams);

        $this->$field = $value;
    }

    private function getColumnsUrl ()
    {
        return sprintf("%s/%d/columns/%s.json", parent::apiEndpoint(), $this->getBoardId(), $this->getId());
    }
}