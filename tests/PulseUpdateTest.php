<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseUpdate;

class PulseUpdatesTest extends PulseUnitTest
{
    /** @var Pulse */
    private $pulseWithUpdates;

    /** @var PulseUpdate[] */
    private $updates;

    public function setUp ()
    {
        parent::setUp();

        $this->pulseWithUpdates = new Pulse(27168882);
        $this->updates = $this->pulseWithUpdates->getUpdates();
    }

    public function testPulseGetTwoUpdates ()
    {
        $this->assertCount(2, $this->updates);
    }

    public function testUpdateGetAuthor ()
    {
        $update = $this->updates[0];

        $this->assertEquals(self::MainUser, $update->getAuthor()->getId());
    }

    public function testUpdateGetUrl()
    {
        $this->assertEquals(
            'https://phppulse.dapulse.com/posts/23611892',
            $this->updates[0]->getUrl()
        );
    }

    public function testUpdateGetID ()
    {
        $this->assertEquals(23611892, $this->updates[0]->getId());
    }

    public function testUpdateGetBody ()
    {
        $this->assertContains('<em>violently</em>', $this->updates[1]->getBody());
    }

    public function testUpdateGetBodyText ()
    {
        $this->assertContains(' violently ', $this->updates[1]->getBodyText());
    }

    public function testUpdateGetKind ()
    {
        $replies = $this->updates[0]->getReplies();

        $this->assertEquals('update', $this->updates[1]->getKind());
        $this->assertEquals('update', $replies[0]->getKind());
    }

    public function testUpdateHasAssets ()
    {
        $this->assertFalse($this->updates[0]->hasAssets());
        $this->assertTrue($this->updates[1]->hasAssets());
    }

    public function testUpdateGetAssetsEmpty ()
    {
        $assets = $this->updates[0]->getAssets();

        $this->assertCount(0, $assets);
    }

    public function testUpdateGetAssets ()
    {
        $assets = $this->updates[1]->getAssets();

        $this->assertIsArray($assets);
        $this->assertCount(1, $assets);
        $this->assertArrayHasKey('resource_content_type', $assets[0]);
    }

    public function testUpdateTimeStamps ()
    {
        $this->assertInstanceOf(\DateTime::class, $this->updates[0]->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $this->updates[0]->getUpdatedAt());
    }

    public function testUpdateWithNoRepliesReturnsEmptyArray ()
    {
        $this->assertEmpty($this->updates[1]->getReplies());
    }

    public function testUpdateWithRepliesReturnsPulseUpdateArray ()
    {
        $replies = $this->updates[0]->getReplies();

        $this->assertCount(1, $replies);
        $this->assertInstanceOf(PulseUpdate::class, $replies[0]);
    }
}