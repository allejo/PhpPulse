<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Objects\PulseColumnColorValue;
use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseBoard;
use allejo\DaPulse\PulseColumn;

class ModificationTest extends PulseUnitTest
{
    /**
     * @var int
     */
    private $userID;

    /**
     * @var PulseBoard
     */
    private $pulseBoard;

    /**
     * @var PulseColumn[]
     */
    private $pulseColumns;

    /**
     * @var int
     */
    private $pulseColumnCount;

    public function setUp()
    {
        parent::setUp();

        $this->userID = 303448;
        $this->pulseBoard = PulseBoard::createBoard("Dynamic Board", $this->userID);
        $this->pulseColumns = $this->pulseBoard->getColumns();
        $this->pulseColumnCount = count($this->pulseColumns);
    }

    public function tearDown()
    {
        $this->pulseBoard->deleteBoard();
    }

    public function columnTypeProvider()
    {
        return array(
            array(PulseColumn::Date),
            array(PulseColumn::Person),
            array(PulseColumn::Status),
            array(PulseColumn::Text)
        );
    }

    public function testBoardCreateColumnWithTooManyColors()
    {
        $this->setExpectedException('allejo\DaPulse\Exceptions\InvalidArraySizeException');

        $colors[11] = "I'm over";

        $this->pulseBoard->createColumn("Dynamic Status", PulseColumn::Status, $colors);
    }

    public function testBoardCreateColumnWithWrongParametersSet()
    {
        $this->setExpectedException('allejo\DaPulse\Exceptions\ArgumentMismatchException');

        $colors = array(
            PulseColumnColorValue::Orange => "Just Started"
        );

        $this->pulseBoard->createColumn("Dynamic Status", PulseColumn::Date, $colors);
    }

    /**
     * @dataProvider columnTypeProvider
     *
     * @param string $columnType The type of column to make
     */
    public function testBoardCreateColumn($columnType)
    {
        $this->pulseBoard->createColumn($columnType . " Column", $columnType);

        $newColumns = $this->pulseBoard->getColumns();
        $newColumnCount = count($newColumns);
        $newestColumn = $newColumns[count($newColumns) - 1];

        $this->assertEquals($this->pulseColumnCount + 1, $newColumnCount);
        $this->assertEquals($columnType, $newestColumn->getType());
    }
}