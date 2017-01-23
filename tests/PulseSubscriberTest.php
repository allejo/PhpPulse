<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\PulseBoard;
use allejo\DaPulse\PulseUser;

class PulseSubscriberTest extends PulseUnitTest
{
    public function testSubscriberGetPulseBoard ()
    {
        $board = new PulseBoard(3844236);
        $subscribers = $board->getSubscribers();

        $this->assertCountEqual(1, $subscribers);
        $this->assertInstanceOf(PulseUser::class, $subscribers[0]);
        $this->assertEquals(self::MainUser, $subscribers[0]->getId());
    }

    public function testSubscriberAddSubscriberAsNormalUser ()
    {
        $board = new PulseBoard(3844236);
        $user  = $board->addSubscriber(self::SecondUser);
        $subs  = $board->getSubscribers();

        $this->assertCount(2, $subs);
        $this->assertEquals(self::SecondUser, $user->getId());
        $this->assertEquals(self::SecondUser, $subs[1]->getId());
    }

    public function testSubscriberRemoveSubscriber ()
    {
        $board = new PulseBoard(3844236);
        $user  = $board->removeSubscriber(self::SecondUser);
        $subs  = $board->getSubscribers();

        $this->assertCount(1, $subs);
        $this->assertEquals(self::MainUser, $subs[0]->getId());
        $this->assertEquals(self::SecondUser, $user->getId());
    }
}