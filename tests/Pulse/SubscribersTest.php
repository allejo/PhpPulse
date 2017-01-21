<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseUser;

class PulseSubscribersTest extends PulseUnitTest
{
    private $user1;
    private $user2;

    public function setUp ()
    {
        $this->markTestIncomplete();

        parent::setUp();

        $this->user1 = 217784;
        $this->user2 = 212350;
    }

    public function testSubscribersOnStaticPulse ()
    {
        $pulse = new Pulse(19307622);
        $subscribers = $pulse->getSubscribers();

        $this->assertCountEqual(1, $subscribers);
    }

    public function testAddingSubscriberAsPulseUser ()
    {
        $pulse     = new Pulse(19307622);
        $roomba    = new PulseUser($this->user2);
        $origCount = count($pulse->getSubscribers());

        $pulse->addSubscriber($roomba);

        return $this->countSubscriberAndAssertAdd($pulse, $origCount);
    }

    /**
     * @depends testAddingSubscriberAsPulseUser
     *
     * @param $arrayOfValues
     */
    public function testRemovingSubscriberAsPulseUser ($arrayOfValues)
    {
        /* @var $pulse Pulse */
        $pulse     = $arrayOfValues['pulse'];
        $roomba    = new PulseUser($this->user2);
        $origCount = count($pulse->getSubscribers());

        $pulse->removeSubscriber($roomba);
        $this->countSubscriberAndAssertRemove($pulse, $origCount);
    }

    public function testAddingSubscriberAsInt ()
    {
        $pulse = new Pulse(19307622);
        $origCount = count($pulse->getSubscribers());

        $pulse->addSubscriber($this->user2);

        return $this->countSubscriberAndAssertAdd($pulse, $origCount);
    }

    /**
     * @depends testAddingSubscriberAsInt
     *
     * @param array $arrayOfValues
     */
    public function testRemovingSubscriberAsInt ($arrayOfValues)
    {
        /* @var $pulse Pulse */
        $pulse = $arrayOfValues['pulse'];
        $origCount = count($pulse->getSubscribers());

        $pulse->removeSubscriber($this->user2);
        $this->countSubscriberAndAssertRemove($pulse, $origCount);
    }

    /**
     * @param Pulse $pulse
     * @param int   $origCount
     *
     * @return array
     */
    private function countSubscriberAndAssertAdd (&$pulse, $origCount)
    {
        $newCount = count($pulse->getSubscribers());

        $this->assertGreaterThan($origCount, $newCount);

        return array(
            "pulse" => $pulse
        );
    }

    /**
     * @param Pulse $pulse
     * @param int   $origCount
     */
    private function countSubscriberAndAssertRemove (&$pulse, $origCount)
    {
        $newCount = count($pulse->getSubscribers());

        $this->assertLessThan($origCount, $newCount);
    }
}