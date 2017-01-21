<?php

$auth = json_decode(file_get_contents(__DIR__ . '/../phpunit-auth.json'), true);
$findAndReplace = json_decode(file_get_contents(__DIR__ . '/sanitize-values.json'), true);
$fixtures = file_get_contents(__DIR__ . '/fixtures/PhpPulseVCR');

$sanitized = str_replace($auth['apiToken'], 'YourApiKeyHere', $fixtures);

foreach ($findAndReplace as $key => $value)
{
    $sanitized = str_replace($key, $value, $sanitized);
}

file_put_contents(__DIR__ . '/fixtures/PhpPulseVCR-sanitized', $sanitized);