<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\Objects\PulseColumnStatusValue;
use allejo\DaPulse\Pulse;
use allejo\DaPulse\PulseUser;

class PulseColumnSettersTest extends PulseUnitTest
{
    private $userId;

    /**
     * @var Pulse
     */
    private $pulse;

    public static function invalidColorValueProvider()
    {
        return array(
            array(-1),
            array(PulseColumnStatusValue::MAX_VALUE + 1),
            array(9.8),
            array('hello world')
        );
    }

    public static function invalidTextProvider()
    {
        return [
            [new \stdClass()],
            [null]
        ];
    }

    public static function invalidNumericProvider()
    {
        return [
            ['hello'],
            [true],
            [new \stdClass()],
            [null]
        ];
    }

    public static function invalidDateTimeProvider()
    {
        return [
            [1483228800],
            ['2017-01-01'],
            [new \stdClass()],
            [null]
        ];
    }

    public static function invalidPersonProvider()
    {
        return [
            [130.4],
            [-1200],
            ['qwerty'],
            [new \stdClass()],
            [null]
        ];
    }

    public static function invalidTimelineProvider()
    {
        return [
            [new \DateTime('2017-01-01'), null],
            [null, new \DateTime('2017-01-01')]
        ];
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
     * @dataProvider invalidColorValueProvider
     */
    public function testSettingStatusColumnWithWrongTypes($color)
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

    /**
     * @dataProvider invalidTextProvider
     */
    public function testSettingTextColumnWithWrongTypes($value)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->pulse->getTextColumn('text')->updateValue($value);
    }

    public function testSettingNumericColumn()
    {
        $value = 25;
        $column = $this->pulse->getNumericColumn('numbers');
        $column->updateValue($value);

        $this->assertEquals($value, $column->getValue());
    }

    /**
     * @dataProvider invalidNumericProvider
     */
    public function testSettingNumericColumnWithWrongTypes($value)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->pulse->getNumericColumn('numbers')->updateValue($value);
    }

    public function testSettingDateColumn()
    {
        $value = new \DateTime('2017-02-01');
        $column = $this->pulse->getDateColumn('due_date');
        $column->updateValue($value);

        $this->assertEquals($value, $column->getValue());
    }

    /**
     * @dataProvider invalidDateTimeProvider
     */
    public function testSettingDateColumnWithWrongTypes($value)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->pulse->getDateColumn('due_date')->updateValue($value);
    }

    public function testSettingTimelineColumn()
    {
        $from = new \DateTime('2016-01-01');
        $to   = new \DateTime('2016-01-26');
        $column = $this->pulse->getTimelineColumn('timeline');
        $column->updateValue($from, $to);

        $this->assertEquals(['from' => $from, 'to' => $to], $column->getValue());
    }

    /**
     * @dataProvider invalidTimelineProvider
     */
    public function testSettingTimelineColumnWithWrongTypes($dateOne, $dateTwo)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->pulse->getTimelineColumn('timeline')->updateValue($dateOne, $dateTwo);
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

    /**
     * @dataProvider invalidPersonProvider
     */
    public function testSettingPersonColumnWithWrongTypes($value)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->pulse->getPersonColumn('person')->updateValue($value);
    }

    public function testSettingNonExistentColumn()
    {
        $this->markTestIncomplete('I need to get the response from DaPulse to create the mock');

        $this->setExpectedException('allejo\DaPulse\Exceptions\ColumnNotFoundException');

        $this->pulse->getTextColumn("non-existent")->updateValue('Hello world');
    }
}
