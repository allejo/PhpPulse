<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseBoard;

class PulseUpdatesTest extends PulseUnitTest
{
    /**
     * @var PulseBoard
     */
    private $board;

    private $userId;

    public static function updateProvider()
    {
        return array(
            array(217784, "Bacon ipsum dolor amet pork venison ham hock prosciutto pork belly chicken turkey capicola rump leberkas corned beef short ribs tail tongue."),
            array(217784, "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent quis tincidunt arcu, et varius augue. Ut vel erat pretium, fermentum.")
        );
    }

    public function setUp ()
    {
        $this->markTestIncomplete();

        parent::setUp();

        $this->board = new PulseBoard(19306968);
        $this->userId = 217784;
    }

    public function testGetNoUpdatesForPulse()
    {
        $pulse = $this->board->createPulse("My Little Mountain Climber Conflict", $this->userId);

        $this->assertCountEqual(0, $pulse->getUpdates());

        $pulse->deletePulse();
    }

    public function testCreateUpdate()
    {
        $pulse = $this->board->createPulse("Neurotic Jackhammer Detective", $this->userId);

        $updateContent = self::updateProvider();

        $pulse->createUpdate($updateContent[0][0], $updateContent[0][1]);
        $pulse->createUpdate($updateContent[1][0], $updateContent[1][1]);

        $this->assertCountEqual(2, $pulse->getUpdates());

        return $pulse;
    }

    /**
     * @depends testCreateUpdate
     *
     * @param Pulse $pulse
     */
    public function testGetUpdates($pulse)
    {
        $updates = $pulse->getUpdates();
        $content = self::updateProvider();

        // Updates are in reverse chronological order
        $this->assertEquals($content[1][0], $updates[0]->getAuthor()->getId());
        $this->assertEquals($content[0][0], $updates[1]->getAuthor()->getId());

        $this->assertEquals($content[1][1], $updates[0]->getBodyText());
        $this->assertEquals($content[0][1], $updates[1]->getBodyText());

        $pulse->deletePulse();
    }
}