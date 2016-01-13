<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Pulse;

class GetValuesDataTypesTest extends PulseUnitTest
{
    private $id;

    /**
     * @var Pulse
     */
    private $pulse;

    public function setUp ()
    {
        parent::setUp();

        $this->id = 3930967;
        $this->pulse = new Pulse($this->id);
    }

    public function testGetUrl ()
    {
        $expectedUrl = sprintf("https://phppulse.dapulse.com/projects/%d", $this->pulse->getId());

        $this->assertIsString($this->pulse->getUrl());
        $this->assertEquals($expectedUrl, $this->pulse->getUrl());
    }

    public function testGetId ()
    {
        $this->assertIsInt($this->pulse->getId());
        $this->assertEquals($this->id, $this->pulse->getId());
    }

    public function testGetName ()
    {
        $this->assertIsString($this->pulse->getName());
        $this->assertEquals("Pulse Name", $this->pulse->getName());
    }

    public function testGetUpdatesCount ()
    {
        $this->assertIsInt($this->pulse->getUpdatesCount());
        $this->assertEquals(1, $this->pulse->getUpdatesCount());
    }

    public function testGetBoardId ()
    {
        $this->assertIsInt($this->pulse->getBoardId());
        $this->assertEquals(3844236, $this->pulse->getBoardId());
    }

    public function testGetCreatedAt ()
    {
        $this->assertInstanceOf('\DateTime', $this->pulse->getCreatedAt());
    }

    public function testGetUpdatedAt ()
    {
        $this->assertInstanceOf('\DateTime', $this->pulse->getUpdatedAt());
    }

    public function testGetGroupId ()
    {
        $this->assertEquals("topics", $this->pulse->getGroupId());
    }
}