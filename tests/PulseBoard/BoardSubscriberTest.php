<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\PulseBoard;

class PulseBoardSubscriberTest extends PulseUnitTest
{
    /**
     * @var PulseBoard
     */
    private $board;

    private $user1;
    private $user2;

    public function setUp ()
    {
        parent::setUp();

        $this->board = new PulseBoard(3844236);
        $this->user1 = 303448;
        $this->user2 = 361981;
    }

    public function testInitialSubscriber ()
    {
        $subscribers = $this->board->getSubscribers();

        $this->assertCountEqual(1, $subscribers);
    }

    public function testAddingSubscriber ()
    {
        $this->board->addSubscriber($this->user2);

        $subscribers = $this->board->getSubscribers();

        $this->assertCountEqual(2, $subscribers);
    }

    /**
     * @depends testAddingSubscriber
     */
    public function testRemovingSubscriber ()
    {
        $this->board->removeSubscriber($this->user2);

        $subscribers = $this->board->getSubscribers();

        $this->assertCountEqual(1, $subscribers);
    }
}