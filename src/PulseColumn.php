<?php

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiObject;

class PulseColumn extends ApiObject
{
    const Date   = "date";
    const Person = "person";
    const Status = "status";
    const Text   = "text";

    protected $id;
    protected $title;
    protected $type;
    protected $empty_text;
    protected $labels;

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
}