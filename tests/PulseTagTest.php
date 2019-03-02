<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\PulseTag;

class PulseTagTest extends PulseUnitTest
{
    /** @var int */
    private $id;

    /** @var PulseTag */
    private $pulseTag;

    public function setUp ()
    {
        parent::setUp();

        $this->id = 1081234;
        $this->pulseTag = new PulseTag($this->id);
    }

    public function testGetId ()
    {
        $this->assertTrue(is_int($this->pulseTag->getId()));
        $this->assertEquals($this->id, $this->pulseTag->getId());
    }

    public function testGetName ()
    {
        $this->assertEquals('barTag2', $this->pulseTag->getName());
    }

    public function testGetColor ()
    {
        $this->assertEquals('black', $this->pulseTag->getColor());
    }

    public function testGetCreatedAt ()
    {
        $this->assertInstanceOf('\DateTime', $this->pulseTag->getCreatedAt());
        $this->assertEquals(new \DateTime('2019-01-22T23:21:54Z'), $this->pulseTag->getCreatedAt());
    }

    public function testGetUpdatedAt ()
    {
        $this->assertInstanceOf('\DateTime', $this->pulseTag->getUpdatedAt());
        $this->assertEquals(new \DateTime('2019-01-20T23:21:54Z'), $this->pulseTag->getUpdatedAt());
    }
}
