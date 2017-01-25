<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse;

use allejo\DaPulse\Objects\ApiObject;

/**
 * Class PulseColumn
 *
 * @api
 * @package allejo\DaPulse
 * @since   0.1.0
 */
class PulseColumn extends ApiObject
{
    const API_PREFIX = "boards";

    const Date     = "date";
    const Numeric  = "numeric";
    const Person   = "person";
    const Status   = "status";
    const Text     = "text";
    const Timeline = "timerange";

    protected $title;
    protected $type;
    protected $empty_text;
    protected $labels;
    protected $board_id;

    public function __construct ($idOrArray)
    {
        $this->arrayConstructionOnly = true;

        parent::__construct($idOrArray);
    }

    public function getId ()
    {
        return $this->id;
    }

    public function getTitle ()
    {
        $this->lazyLoad();

        return $this->title;
    }

    public function getType ()
    {
        $this->lazyLoad();

        // @todo Workaround due to a bug in DaPulse's API see: https://github.com/allejo/PhpPulse/issues/5
        if ($this->type === "color")
        {
            $this->type = self::Status;
        }

        return $this->type;
    }

    public function getEmptyText ()
    {
        $this->lazyLoad();

        return $this->empty_text;
    }

    public function getLabels ()
    {
        $this->lazyLoad();

        return $this->labels;
    }

    public function getBoardId ()
    {
        $this->lazyLoad();

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

        $postParams = [
            $field => $value
        ];

        self::sendPut($this->getColumnsUrl(), $postParams);

        $this->$field = $value;
    }

    private function getColumnsUrl ()
    {
        return sprintf("%s/%d/columns/%s.json", parent::apiEndpoint(), $this->getBoardId(), $this->getId());
    }
}