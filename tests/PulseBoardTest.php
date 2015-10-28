<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\PulseBoard;

class PulseBoardTest extends PulseUnitTest
{
    private $id;

    /**
     * @var PulseBoard
     */
    private $board;

    public function setUp()
    {
        parent::setUp();

        $this->id = 3844236;
        $this->board = new PulseBoard($this->id);
    }

    public function testBoardObjectNotNull()
    {
        $this->assertNotNull($this->board);
    }

    public function testBoardUrl()
    {
        $expectedURL = "https://phppulse.dapulse.com/boards/" . $this->id;
        $this->assertEquals($expectedURL, $this->board->getUrl());
    }

    public function testBoardId()
    {
        $this->assertEquals($this->id, $this->board->getId());
    }

    public function testBoardName()
    {
        $expectedValue = "Static Pulse Board";
        $this->assertEquals($expectedValue, $this->board->getName());
    }

    public function testBoardDescription()
    {
        $expectedValue = "This is a static PulseBoard that PhpPulse will look for in its unit tests but will NOT modify.";
        $this->assertEquals($expectedValue, $this->board->getDescription());
    }

    public function testBoardColumnsNotNull()
    {
        $this->assertNotNull($this->board->getColumns());
    }

    public function testBoardColumnsCount()
    {
        $this->assertCountGreaterThan(0, $this->board->getColumns());
    }

    public function testBoardColumnsType()
    {
        $column = $this->board->getColumns()[0];

        $this->assertPulseObjectType("PulseColumn", $column);
    }

    public function testBoardGroupsNotNull()
    {
        $this->assertNotNull($this->board->getGroups());
    }

    public function testBoardGroupType()
    {
        $group = $this->board->getGroups()[0];

        $this->assertPulseObjectType("PulseGroup", $group);
    }
}