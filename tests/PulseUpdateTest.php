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

    public function testUpdateLiking ()
    {
        $result = $this->updates[0]->likeUpdate(self::MainUser);

        $this->assertTrue($result);
    }

    public function testUpdateUnlikingFromUserWhoLikedUpdated ()
    {
        $result = $this->updates[0]->unlikeUpdate(self::MainUser);

        $this->assertTrue($result);
    }

    public function testUpdateUnlikingFromUserWhoDidntLikeUpdate ()
    {
        $result = $this->updates[0]->unlikeUpdate(self::SecondUser);

        $this->assertTrue($result);
    }

    public function testGetAllUpdates ()
    {
        $updates = PulseUpdate::getUpdates();

        $this->assertGreaterThanOrEqual(2, $updates);
    }

    public function testGetAllUpdatesSinceFromDateTime ()
    {
        $updates = PulseUpdate::getUpdates([
            'since' => new \DateTime('2017-01-01')
        ]);

        $this->assertCount(2, $updates);
    }

    public function testGetAllUpdatesSinceFromUnixTimestamp ()
    {
        $updates = PulseUpdate::getUpdates([
            'since' => '1483228800' // 2017-01-01 as a Unix timestamp
        ]);

        $this->assertCount(2, $updates);
    }

    public function testGetAllUpdatesSinceFromStringTimestamp ()
    {
        $updates = PulseUpdate::getUpdates([
            'since' => '2017-01-01'
        ]);

        $this->assertCount(2, $updates);
    }

    public function testGetAllUpdatesUntil ()
    {
        $this->markTestSkipped('DaPulse API requests are limiting a return of 25 updates only...?');

        $timeSplit     = new \DateTime('2017-01-01');
        $allUpdates    = PulseUpdate::getUpdates();
        $recentUpdates = PulseUpdate::getUpdates([
            'since' => $timeSplit
        ]);
        $oldUpdates    = PulseUpdate::getUpdates([
            'until' => $timeSplit
        ]);

        $this->assertEquals(count($allUpdates), count($recentUpdates) + count($oldUpdates));
    }
}