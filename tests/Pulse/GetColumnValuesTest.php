<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Objects\PulseColumnStatusValue;
use allejo\DaPulse\Objects\PulseColumnValue;
use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseBoard;
use allejo\DaPulse\PulseUser;

class GetColumnValuesTest extends PulseUnitTest
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

        $this->id = 3844236;
        $this->userId = 303448;
        $this->board = new PulseBoard($this->id);
        $this->pulses = $this->board->getPulses();
    }

    public function testGetNameColumnValues()
    {
        $expectedValues = array(
            "Pulse Name",
            "A Sample Green Pulse",
            "Another Sibling Pulse",
            "A Third Pulse"
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
            "Hello World",
            "Canadian Bacon",
            "Bar",
            "Qux"
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

        $this->assertEquals($user, $this->pulses[1]->getPersonColumn('person')->getValue());
    }

    public function testGetDateColumnValues()
    {
        $date = array(
            new \DateTime("2015-10-31"),
            new \DateTime("2015-12-05"),
            new \DateTime("2015-11-18")
        );

        $this->assertEquals($date[0], $this->pulses[1]->getDateColumn('due_date')->getValue());
        $this->assertEquals($date[1], $this->pulses[2]->getDateColumn('due_date')->getValue());
        $this->assertEquals($date[2], $this->pulses[3]->getDateColumn('due_date')->getValue());
    }

    public function testGetStatusColumnValues()
    {
        $expectedValues = array(
            PulseColumnStatusValue::Orange,
            PulseColumnStatusValue::Purple,
            PulseColumnStatusValue::Orange,
            PulseColumnStatusValue::L_Green
        );
        $count = count($expectedValues);

        for ($i = 0; $i < $count; $i++)
        {
            $this->assertEquals($expectedValues[$i], $this->pulses[$i]->getStatusColumn('status')->getValue());
        }
    }

    public function testGetBadColumnType()
    {
        $this->setExpectedException('allejo\DaPulse\Exceptions\InvalidObjectException');

        PulseColumnValue::_createColumnType("non-existent", array());
    }
}