<?php

use allejo\DaPulse\Pulse;
use allejo\DaPulse\Tests\AuthenticatedClient;
use VCR\VCR;

if (!file_exists(__DIR__ . "/../vendor/autoload.php"))
{
    die(
        "\n[ERROR] You need to run composer before running the test suite.\n".
        "To do so run the following commands:\n".
        "    curl -s http://getcomposer.org/installer | php\n".
        "    php composer.phar install\n\n"
    );
}

require_once __DIR__ . '/../vendor/autoload.php';

// VCR needs to be loaded immediately after the autoloader or else it will not be able to intercept URL calls
VCR::turnOn();

// For when it's time to update PHPUnit
//if (!class_exists('\PHPUnit_Framework_TestCase') && class_exists('\PHPUnit\Framework\TestCase'))
//{
//    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
//}

$authClient = new AuthenticatedClient("phpunit-auth.json");

if (!$authClient->isAuthenticationSetup())
{
    die("Either set the `apiToken` environment variable or create a `phpunit-auth.json` file.\n");
}

Pulse::setApiKey($authClient->getApiToken());
