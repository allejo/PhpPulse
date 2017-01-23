<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseBoard;
use allejo\DaPulse\PulseColumn;
use allejo\DaPulse\PulseGroup;

class PulseBoardTest extends PulseUnitTest
{
    private $id;

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
        $this->board = new PulseBoard($this->id);
        $this->pulses = $this->board->getPulses();
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
        $expectedValue = "API Mocks";
        $this->assertEquals($expectedValue, $this->board->getName());
    }

    public function testGetBoardDescription()
    {
        $expectedValue = "A DaPulse board used for unit testing and providing mocks for the PhpPulse library we use and maintain";
        $this->assertEquals($expectedValue, $this->board->getDescription());
    }

    public function testGetBoardColumnsCount()
    {
        $this->assertCountGreaterThan(0, $this->board->getColumns());
    }

    public function testGetBoardColumnsType()
    {
        $column = $this->board->getColumns();

        $this->assertInstanceOf(PulseColumn::class, $column[0]);
    }

    public function testGetBoardGroupType()
    {
        $group = $this->board->getGroups();

        $this->assertInstanceOf(PulseGroup::class, $group[0]);
    }

    public function testGetBoardCreatedAt()
    {
        $this->assertInstanceOf(\DateTime::class, $this->board->getCreatedAt());
    }

    public function testGetBoardUpdatedAt()
    {
        $this->assertInstanceOf(\DateTime::class, $this->board->getUpdatedAt());
    }

    public function testGetPulses()
    {
        $this->assertCountEqual(4, $this->pulses);
    }

    public function testGroupOfPulseWithApiCall()
    {
        $newPulse = new Pulse(27157096);

        $this->assertEquals('topics', $newPulse->getGroupId());
    }
}