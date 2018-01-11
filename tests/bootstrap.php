<?php
/**
 * @copyright 2018 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

require __DIR__ . '/../vendor/autoload.php';

use allejo\DaPulse\PulseBoard;
use allejo\DaPulse\Tests\AuthenticatedClient;
use VCR\VCR;

VCR::configure()
   ->setMode('none')
;

$authClient = new AuthenticatedClient('phpunit-auth.json');

if (!$authClient->isAuthenticationSetup())
{
    $this->markTestSkipped();
}

PulseBoard::setApiKey($authClient->getApiToken());
