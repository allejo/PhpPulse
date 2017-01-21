<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Objects\PulseColumnStatusValue;
use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseUser;

class PulseSetColumnsTest extends PulseUnitTest
{
    private $userId;

    /**
     * @var Pulse
     */
    private $pulse;

    public static function invalidColorValues()
    {
        return array(
            array(-1),
            array(11),
            array(9.8),
            array('hello world')
        );
    }

    public function setUp()
    {
        parent::setUp();

        $this->userId = self::MainUser;
        $this->pulse = new Pulse(27168882);
    }

    public function testSettingStatusColumn()
    {
        $value = PulseColumnStatusValue::Gold;
        $column = $this->pulse->getStatusColumn('status');
        $column->updateValue($value);

        $this->assertEquals($value, $column->getValue());
    }

    /**
     * @dataProvider invalidColorValues
     */
    public function testSettingInvalidStatusColumn($color)
    {
        $this->setExpectedException('\InvalidArgumentException');

        $this->pulse->getStatusColumn('status')->updateValue($color);
    }

    public function testSettingTextColumn()
    {
        $value = 'Elastic Water Bottle';
        $column = $this->pulse->getTextColumn('text');
        $column->updateValue($value);

        $this->assertEquals($value, $column->getValue());
    }

    public function testSettingNumericColumn()
    {
        $value = 25;
        $column = $this->pulse->getNumericColumn('numbers');
        $column->updateValue($value);

        $this->assertEquals($value, $column->getValue());
    }

    public function testSettingDateColumn()
    {
        $value = new \DateTime('2017-02-01');
        $column = $this->pulse->getDateColumn('due_date');
        $column->updateValue($value);

        $this->assertEquals($value, $column->getValue());
    }

    public function testSettingTimelineColumn()
    {
        $from = new \DateTime('2016-01-01');
        $to   = new \DateTime('2016-01-26');
        $column = $this->pulse->getTimelineColumn('timeline');
        $column->updateValue($from, $to);

        $this->assertEquals(['from' => $from, 'to' => $to], $column->getValue());
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