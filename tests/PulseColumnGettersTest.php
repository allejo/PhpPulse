<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Objects\PulseColumnStatusValue;
use allejo\DaPulse\Objects\PulseColumnValue;
use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseBoard;
use allejo\DaPulse\PulseUser;

class PulseColumnGettersTest extends PulseUnitTestCase
{
    private $id;

    private $userId;

    /**
     * @var PulseBoard
     */
    private $board;

    /**
     * @var Pulse[]
     */
    private $pulses;

    public function setUp()
    {
        parent::setUp();

        $this->id = self::BoardId; // Board ID
        $this->userId = self::MainUser; // Main User Account

        $this->board = new PulseBoard($this->id);
        $this->pulses = $this->board->getPulses(25, 1);
    }

    public function testGetNameColumnValues()
    {
        $expectedValues = array(
            "Mock Pulse One",
            "Mock Pulse Two",
            "Mockery Onion",
            "Jar of Jam"
        );
        $count = count($expectedValues);

        for ($i = 0; $i < $count; $i++)
        {
            $this->assertEquals($expectedValues[$i], $this->pulses[$i]->getName());
        }
    }

    public function testGetTextColumnValues()
    {
        $expectedValues = array(
            "Silver Pet Rock",
            "Oblivious Platypus",
            "Aspiring Platinum Duck",
            null
        );
        $count = count($expectedValues);

        for ($i = 0; $i < $count; $i++)
        {
            $this->assertEquals($expectedValues[$i], $this->pulses[$i]->getTextColumn('text')->getValue());
        }
    }

    public function testGetPersonColumnValues()
    {
        $user = new PulseUser($this->userId);

        $this->assertEquals($user, $this->pulses[0]->getPersonColumn('person')->getValue());
    }

    public function testGetPersonNullColumn()
    {
        $this->assertNull($this->pulses[1]->getPersonColumn('person')->getValue());
    }

    public function testGetDateNullColumn()
    {
        $this->assertNull($this->pulses[2]->getDateColumn('due_date')->getValue());
    }

    public function testGetDateColumnValues()
    {
        $date = array(
            new \DateTime("2017-01-03"),
            new \DateTime("2016-10-31"),
            null
        );

        $this->assertEquals($date[0], $this->pulses[0]->getDateColumn('due_date')->getValue());
        $this->assertEquals($date[1], $this->pulses[1]->getDateColumn('due_date')->getValue());
        $this->assertEquals($date[2], $this->pulses[2]->getDateColumn('due_date')->getValue());
    }

    public function testGetStatusColumnValues()
    {
        $expectedValues = array(
            PulseColumnStatusValue::Orange,
            PulseColumnStatusValue::Red,
            PulseColumnStatusValue::L_Green,
            PulseColumnStatusValue::Grey
        );
        $count = count($expectedValues);

        for ($i = 0; $i < $count; $i++)
        {
            $this->assertEquals($expectedValues[$i], $this->pulses[$i]->getStatusColumn('status')->getValue());
        }
    }

    public function testGetNumericColumnValue()
    {
        $this->assertEquals(100.5, $this->pulses[0]->getNumericColumn('numbers')->getValue());
        $this->assertEquals(50, $this->pulses[1]->getNumericColumn('numbers')->getValue());
    }

    public function testGetNumericColumnNullValue()
    {
        $this->assertNull($this->pulses[2]->getNumericColumn('numbers')->getValue());
    }

    public function testGetTimelineColumnValue()
    {
        $this->assertEquals([
            'from' => new \DateTime('2017-01-09'),
            'to'   => new \DateTime('2017-01-15')
        ], $this->pulses[0]->getTimelineColumn('timeline')->getValue());
    }

    public function testGetTimelineColumnNullValue()
    {
        $this->assertNull($this->pulses[1]->getTimelineColumn('timeline')->getValue());
    }

    public function testGetBadColumnType()
    {
        $this->setExpectedException('allejo\DaPulse\Exceptions\InvalidObjectException');

        PulseColumnValue::_createColumnType("non-existent", array());
    }

    public function testGetNonExistentColumn()
    {
        $this->setExpectedException('allejo\DaPulse\Exceptions\ColumnNotFoundException');

        $this->pulses[1]->getDateColumn("non-existent")->getValue();
    }

    public function testGetWrongColumnType()
    {
        $this->setExpectedException('allejo\DaPulse\Exceptions\InvalidColumnException');

        $this->pulses[1]->getDateColumn('person')->getValue();
    }
}
