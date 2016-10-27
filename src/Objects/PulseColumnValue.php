<?php

/**
 * This file contains the PulseColumnValue class
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\Exceptions\InvalidObjectException;
use allejo\DaPulse\PulseColumn;

/**
 * The base class used for column values belonging to a specified class
 *
 * @internal
 * @package allejo\DaPulse\Objects
 * @since   0.1.0
 */
abstract class PulseColumnValue extends ApiObject
{
    const API_PREFIX = "boards";

    /**
     * The ID of the parent board that this column's Pulse belongs to.
     *
     * @var int
     */
    protected $board_id;

    /**
     * The ID of the current column. This is a unique identifier when accessing columns through the API.
     *
     * @var string
     */
    protected $column_id;

    /**
     * The ID of the Pulse this column value belongs to
     *
     * @var int
     */
    protected $pulse_id;

    /**
     * The value that this column has. The data type can be an integer, string, or DateTime depending on the column type
     *
     * @var mixed
     */
    protected $column_value;

    /**
     * This constructor only accepts an array of the data regarding a specific column
     *
     * @internal
     *
     * @param array $array An associative array containing information regarding a column's value
     *
     * @since 0.1.0
     *
     * @throws \InvalidArgumentException An ID is given when an instance of this object can only be created from an
     *                                   array of existing data
     */
    public function __construct ($array)
    {
        $this->arrayConstructionOnly = true;

        parent::__construct($array);
    }

    /**
     * Create the appropriate object based on the type of column
     *
     * @internal
     *
     * @param string $type The type of column as specified by DaPulse's API; i.e. 'text', 'date', 'status', 'person'
     * @param array  $data An associative array containing data regarding the column
     *
     * @since 0.1.0
     *
     * @todo  Fix the hardcoded "color" case statement. See: https://github.com/allejo/PhpPulse/issues/5
     *
     * @throws InvalidObjectException
     *
     * @return PulseColumnStatusValue|PulseColumnDateValue|PulseColumnNumericValue|PulseColumnPersonValue|PulseColumnTextValue
     */
    public static function _createColumnType ($type, $data)
    {
        switch ($type)
        {
            case PulseColumn::Text:
                return (new PulseColumnTextValue($data));

            case "color":
            case PulseColumn::Status:
                return (new PulseColumnStatusValue($data));

            case PulseColumn::Numeric:
                return (new PulseColumnNumericValue($data));

            case PulseColumn::Person:
                return (new PulseColumnPersonValue($data));

            case PulseColumn::Date:
                return (new PulseColumnDateValue($data));
        }

        throw new InvalidObjectException("'$type' is an unsupported column type to modify.");
    }

    /**
     * Check whether to return null because a column's value does not exist.
     *
     * @return bool True if the column does not have a value
     */
    protected function isNullValue ()
    {
        return (is_null($this->jsonResponse["value"]) && !isset($this->column_value));
    }

    abstract public function getValue ();

    abstract public function updateValue ($updateValue);
}