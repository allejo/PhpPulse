<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Objects\PulseColumnStatusValue;
use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseBoard;
use allejo\DaPulse\PulseUser;

class PulseSetColumnsTest extends PulseUnitTest
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

    public static function invalidColorValues()
    {
        return array(
            array(-1),
            array(11),
            array(9.8)
        );
    }

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

    /**
     * @dataProvider invalidColorValues
     */
    public function testSettingInvalidStatusColumn($color)
    {
        $this->setExpectedException('\InvalidArgumentException');

        $this->pulse->getStatusColumn("status")->updateValue($color);
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

    public function testSettingPersonColumnFromInt()
    {
        $column = $this->pulse->getPersonColumn('person');
        $column->updateValue($this->userId);

        $this->assertEquals($this->userId, $column->getValue()->getId());
    }

    public function testSettingPersonColumnFromObject()
    {
        $user = new PulseUser($this->userId);
        $column = $this->pulse->getPersonColumn('person');

        $column->updateValue($user);

        $this->assertEquals($user, $column->getValue());
    }
}