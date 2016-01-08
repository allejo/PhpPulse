<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Objects\PulseColumnStatusValue;
use allejo\DaPulse\Objects\PulseColumnValue;
use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseBoard;
use allejo\DaPulse\PulseUser;

class SetColumnValuesTest extends PulseUnitTest
{
    private $userId;

    /**
     * @var PulseBoard
     */
    private $board;

    /**
     * @var Pulse
     */
    private $pulse;

    public function setUp()
    {
        parent::setUp();

        $this->userId = 303448;
        $this->board = new PulseBoard(5370166);
        $this->pulse = $this->board->createPulse("Dynamic Pulse", $this->userId);
    }

    public function tearDown ()
    {
        $this->pulse->deletePulse();
    }

    public function testSettingStatusColumn()
    {
        $value = PulseColumnStatusValue::Gold;

        $this->pulse->getStatusColumn("status")->updateValue($value);

        $pulse = new Pulse($this->pulse->getId());

        $this->assertEquals($value, $pulse->getStatusColumn("status")->getValue());
        $this->assertEquals($value, $this->pulse->getStatusColumn("status")->getValue());
    }

    public function testSettingTextColumn()
    {
        $value = "Bacon";

        $this->pulse->getTextColumn("text")->updateValue($value);

        $pulse = new Pulse($this->pulse->getId());

        $this->assertEquals($value, $pulse->getTextColumn("text")->getValue());
        $this->assertEquals($value, $this->pulse->getTextColumn("text")->getValue());
    }

    public function testSettingDateColumn()
    {
        $value = new \DateTime("2017-02-01");

        $this->pulse->getDateColumn("due_date")->updateValue($value);

        $pulse = new Pulse($this->pulse->getId());

        $this->assertEquals($value, $pulse->getDateColumn("due_date")->getValue());
        $this->assertEquals($value, $this->pulse->getDateColumn("due_date")->getValue());
    }
}