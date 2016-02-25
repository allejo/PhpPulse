# PhpPulse [![Stable Release](https://img.shields.io/packagist/v/allejo/php-pulse.svg)](https://packagist.org/packages/allejo/php-pulse) [![Build Status](https://travis-ci.org/allejo/PhpPulse.svg?branch=master)](https://travis-ci.org/allejo/PhpPulse) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/allejo/PhpPulse/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/allejo/PhpPulse/?branch=master) [![Coverage Status](https://coveralls.io/repos/allejo/PhpPulse/badge.svg?branch=master&service=github)](https://coveralls.io/github/allejo/PhpPulse?branch=master)

[![Join the chat at https://gitter.im/allejo/PhpPulse](https://badges.gitter.im/allejo/PhpPulse.svg)](https://gitter.im/allejo/PhpPulse?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

A PHP wrapper for working with the [DaPulse API](https://developers.dapulse.com/).

## Requirements

- PHP 5.3+

## Usage

**Accessing Boards**

```php
<?php

// Set our API key to access the API
PulseBoard::setApiKey("DaPulse API Key");

// Get all of our boards
$boards = PulseBoard::getBoards();

foreach ($boards as $board)
{
    echo "Board Name: $board->getName()";
    echo "Board Description: $board->getDescription()";

    // Create a Pulse with the owner of user id 1
    $pulse = $board->createPulse("Sample Pulse Title", 1);
}
```

## Getting Help

To get help, you may either [create an issue](https://github.com/allejo/PhpPulse/issues) or stop by IRC; I'm available on IRC as "allejo" so feel free to ping me. I recommend creating an issue in case others have the same question but for quick help, IRC works just fine.

To report a bug or request a feature, please submit an issue.

### Documentation

I am in the process of writing extensive documentation on how to use this library, so please bear with me. If you would like to contribute, reach out to me.

### IRC

Channel: **#sujevo**  
Network: irc.freenode.net

## License

[MIT](https://github.com/allejo/PhpPulse/blob/master/LICENSE.md)
