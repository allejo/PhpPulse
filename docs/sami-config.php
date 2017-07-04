<?php

require_once __DIR__ . '/ApiFilter.php';

use allejo\Sami\ApiFilter;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir = __DIR__ . '/../src')
;

// generate documentation for all 0.* branches and the master branch
$versions = GitVersionCollection::create($dir)
    ->add('0.3', '0.3 branch')
    ->add('0.2', '0.2 branch')
    ->add('master', 'master branch')
;

$sami = new Sami($iterator, array(
    'versions'             => $versions,
    'title'                => 'PhpPulse API',
    'build_dir'            => __DIR__ . '/api/build/PhpPulse/%version%',
    'cache_dir'            => __DIR__ . '/api/cache/PhpPulse/%version%',
    'remote_repository'    => new GitHubRemoteRepository('allejo/PhpPulse', dirname($dir)),
    'default_opened_level' => 2,
));

/*
 * Include this section if you want sami to document
 * private and protected functions/properties
 */
$sami['filter'] = function () {
    return new ApiFilter();
};

return $sami;
