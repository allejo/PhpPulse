<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Objects\PulseColumnStatusValue;
use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseBoard;
use allejo\DaPulse\PulseUser;

class GettersTest extends PulseUnitTest
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

    public function testGetBoardObjectNotNull()
    {
        $this->assertNotNull($this->board);
    }

    public function testGetBoardUrl()
    {
        $expectedURL = "https://phppulse.dapulse.com/boards/" . $this->id;
        $this->assertEquals($expectedURL, $this->board->getUrl());
    }

    public function testGetBoardId()
    {
        $this->assertEquals($this->id, $this->board->getId());
    }

    public function testGetBoardName()
    {
        $expectedValue = "Static Pulse Board";
        $this->assertEquals($expectedValue, $this->board->getName());
    }

    public function testGetBoardDescription()
    {
        $expectedValue = "This is a static PulseBoard that PhpPulse will look for in its unit tests but will NOT modify.";
        $this->assertEquals($expectedValue, $this->board->getDescription());
    }

    public function testGetBoardColumnsNotNull()
    {
        $this->assertNotNull($this->board->getColumns());
    }

    public function testGetBoardColumnsCount()
    {
        $this->assertCountGreaterThan(0, $this->board->getColumns());
    }

    public function testGetBoardColumnsType()
    {
        $column = $this->board->getColumns();

        $this->assertPulseObjectType("PulseColumn", $column[0]);
    }

    public function testGetBoardGroupsNotNull()
    {
        $this->assertNotNull($this->board->getGroups());
    }

    public function testGetBoardGroupType()
    {
        $group = $this->board->getGroups();

        $this->assertPulseObjectType("PulseGroup", $group[0]);
    }

    public function testGetBoardCreatedAt()
    {
        $this->assertInstanceOf("DateTime", $this->board->getCreatedAt());
    }

    public function testGetBoardUpdatedAt()
    {
        $this->assertInstanceOf("DateTime", $this->board->getUpdatedAt());
    }

    public function testGetPulses()
    {
        $this->assertCountEqual(4, $this->pulses);
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
}