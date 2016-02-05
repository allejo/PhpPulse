<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseBoard;

class PulseBoardGettersTest extends PulseUnitTest
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

    public function testGroupOfPulseWithApiCall()
    {
        $newPulse = new Pulse(3930967);

        $this->assertEquals("topics", $newPulse->getGroupId());
    }
}