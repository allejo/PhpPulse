<?php

namespace allejo\DaPulse\Tests;

use allejo\DaPulse\PulseBoard;
use PHPUnit_Framework_TestCase;

abstract class PulseUnitTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $authClient = new AuthenticatedClient("phpunit-auth.json");

        if (!$authClient->isAuthenticationSetup())
        {
            $this->markTestSkipped();
        }

        PulseBoard::setApiKey($authClient->getApiToken());
    }

    protected function assertIsInt($expected, $message = "")
    {
        $this->assertTrue(is_int($expected), $message);
    }

    protected function assertIsString($expected, $message = "")
    {
        $this->assertTrue(is_string($expected), $message);
    }

    protected function assertCountEqual($expected, $actual, $message = "")
    {
        $this->assertEquals($expected, count($actual), $message);
    }

    protected function assertCountLessThan($expected, $actual, $message = "")
    {
        $this->assertLessThan($expected, count($actual), $message);
    }

    protected function assertCountGreaterThan($expected, $actual, $message = "")
    {
        $this->assertGreaterThan($expected, count($actual), $message);
    }

    protected function assertPulseObjectType($instanceName, $actual, $message = "")
    {
        $instanceOf = "allejo\\DaPulse\\" . $instanceName;

        $this->assertInstanceOf($instanceOf, $actual, $message);
    }

    protected function assertPulseArrayContains($needle, $haystack, $message = "")
    {
        $resultFound = false;

        foreach ($haystack as $hay)
        {
            if ($needle->getId() === $hay->getId())
            {
                $resultFound = true;
                break;
            }
        }

        $this->assertTrue($resultFound, $message);
    }
}