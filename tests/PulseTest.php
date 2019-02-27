<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Pulse;

class PulseGettersTest extends PulseUnitTest
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Pulse
     */
    private $pulse;

    public function setUp ()
    {
        parent::setUp();

        $this->id = 27157096;
        $this->pulse = new Pulse($this->id);
    }

    public function testGetUrl ()
    {
        $expectedUrl = sprintf("https://phppulse.monday.com/projects/%d", $this->pulse->getId());

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
        $this->assertEquals('Mock Pulse One', $this->pulse->getName());
    }

    public function testGetUpdatesCount ()
    {
        $this->assertIsInt($this->pulse->getUpdatesCount());
        $this->assertEquals(0, $this->pulse->getUpdatesCount());
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
        $this->assertEquals('topics', $this->pulse->getGroupId());
    }

    public function testEditNameThroughInstance ()
    {
        $pulse = new Pulse(27345095);

        $this->assertEquals('Pulse to rename', $pulse->getName());

        $newValue = 'Violent toast';
        $pulse->editName($newValue);

        $this->assertEquals($newValue, $pulse->getName());
    }

    public function testArchivePulseThroughInstance ()
    {
        $pulse = new Pulse(27345279);
        $orig = $pulse->getUpdatedAt();
        $pulse->archivePulse();

        $this->assertGreaterThanOrEqual($orig, $pulse->getUpdatedAt());
    }

    public function testDeletePulseThroughInstance ()
    {
        $pulse = new Pulse(27345578);
        $orig = $pulse->getUpdatedAt();
        $pulse->deletePulse();

        $this->assertGreaterThan($orig, $pulse->getUpdatedAt());
    }

    public function testGetTagColumn ()
    {
        $tagColumn = $this->pulse->getTagColumn('has_tags');

        $tags = $tagColumn->getValue();

        $this->assertPulseObjectType('PulseTag', $tags[0]);
    }

    public function testGetTagColumnValue ()
    {
        $tags = $this->pulse->getTagColumn('has_tags')->getValue();
        $tag = $tags[0];

        $this->assertEquals(1081234, $tag->getId());
        $this->assertEquals('black', $tag->getColor());
    }
}
