<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
	->in(__DIR__ . DIRECTORY_SEPARATOR . 'src')
	->name('*.php')
	->ignoreDotFiles(true)
	->ignoreVCS(true);

return (new Config())
	->setIndent("\t")
	->setCacheFile(__DIR__ . DIRECTORY_SEPARATOR . '.php-cs-fixer.cache')
	->setFinder($finder);
