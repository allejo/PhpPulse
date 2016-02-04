<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseUser;

class PulseSubscribersTest extends PulseUnitTest
{
    /**
     * @var Pulse
     */
    private $pulse;

    private $user1;
    private $user2;

    public function setUp ()
    {
        parent::setUp();

        $this->pulse = new Pulse(3930967);
        $this->user1 = 303448;
        $this->user2 = 361981;
    }

    public function testSubscribersOnStaticPulse ()
    {
        $subscribers = $this->pulse->getSubscribers();

        $this->assertCountEqual(2, $subscribers);
    }

    public function testSubscriberMembership ()
    {
        $subscribers = $this->pulse->getSubscribers();

        foreach ($subscribers as $subscriber)
        {
            switch ($subscriber->getId())
            {
                // Main Account
                case $this->user1:
                {
                    $this->assertEquals("admin", $subscriber->getMembership());
                }
                break;

                // PhpPulse Roomba
                case $this->user2:
                {
                    $this->assertEquals("subscriber", $subscriber->getMembership());
                }
                break;

                default:
                    break;
            }
        }
    }

    public function testAddingSubscriberAsPulseUser ()
    {
        $pulse     = new Pulse(3855117);
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
        $pulse = new Pulse(3855117);
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