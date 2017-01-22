# PhpPulse

[![Stable Release](https://img.shields.io/packagist/v/allejo/php-pulse.svg)](https://packagist.org/packages/allejo/php-pulse)
[![Build Status](https://travis-ci.org/allejo/PhpPulse.svg?branch=develop)](https://travis-ci.org/allejo/PhpPulse)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/allejo/PhpPulse/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/allejo/PhpPulse/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/allejo/PhpPulse/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/allejo/PhpPulse/?branch=develop)

A PHP wrapper for working with the [DaPulse API](https://developers.dapulse.com/).

## Requirements

PHP 5.5+

- This library may work with with PHP 5.4, however it's no longer supported
- If you need PHP 5.3 or 5.4 support, your best bet is using the 0.2.x branch, however it's no longer supported


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

I've done my best to document this project and the [generated phpDoc is available](http://docs.allejo.io/PhpPulse/). If you'd like to help with writing documentation or tutorials, I gladly welcome contributions to [this project's wiki](https://github.com/allejo/PhpPulse/wiki).

### IRC

Channel: **#sujevo**  
Network: irc.freenode.net

## License

[MIT](https://github.com/allejo/PhpPulse/blob/master/LICENSE.md)
