<?php

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\Exceptions\InvalidObjectException;

/**
 * Class PulseColumnValue
 *
 * @package allejo\DaPulse\Objects
 */
abstract class PulseColumnValue extends ApiObject
{
    const API_PREFIX = "boards";

    protected $board_id;
    protected $column_id;
    protected $pulse_id;
    protected $value;

    public function __construct($array)
    {
        $this->arrayConstructionOnly = true;

        parent::__construct($array);
    }

    public static function createColumnType ($type, $data)
    {
        switch ($type)
        {
            case "text":
                return (new PulseColumnTextValue($data));

            case "color":
                return (new PulseColumnColorValue($data));

            case "person":
                return (new PulseColumnPersonValue($data));

            case "date":
                return (new PulseColumnDateValue($data));
        }

        throw new InvalidObjectException("'$type' is an unsupported column type to modify.");
    }

    abstract public function getValue();
    abstract public function updateValue($updateValue);
}