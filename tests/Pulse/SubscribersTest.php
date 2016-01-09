<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Pulse;

class SubscribersTest extends PulseUnitTest
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

    public function testAddingSubscriber ()
    {
        $pulse = new Pulse(3855117);
        $origCount = count($pulse->getSubscribers());

        $pulse->addSubscriber($this->user2);

        $newCount = count($pulse->getSubscribers());

        $this->assertGreaterThan($origCount, $newCount);

        return array(
            "pulse" => $pulse
        );
    }

    /**
     * @depends testAddingSubscriber
     *
     * @param array $arrayOfValues
     */
    public function testRemovingSubscriber($arrayOfValues)
    {
        /* @var $pulse Pulse */
        $pulse = $arrayOfValues['pulse'];
        $origCount = count($pulse->getSubscribers());

        $pulse->removeSubscriber($this->user2);

        $newCount = count($pulse->getSubscribers());

        $this->assertLessThan($origCount, $newCount);
    }
}