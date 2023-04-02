<?php


declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in('src');

$config = new Config();
$config->setRules([
    '@Symfony' => true,
    'no_alternative_syntax' => true,
    'strict_comparison' => true,
    'strict_param' => true,
    'declare_strict_types' => true,
    'yoda_style' => false,
])
->setFinder($finder)
->setUsingCache(false)
->setRiskyAllowed(true);

return $config;
