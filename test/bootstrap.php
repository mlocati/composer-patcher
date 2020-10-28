<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

define('COMPOSER_PATCHER_TEST_DIRROOT', str_replace(DIRECTORY_SEPARATOR, '/', dirname(__DIR__)));
define('COMPOSER_PATCHER_TEST_DIRTEST', str_replace(DIRECTORY_SEPARATOR, '/', __DIR__));
define('COMPOSER_PATCHER_TEST_DIRTMP', COMPOSER_PATCHER_TEST_DIRTEST.'/tmp');
if (!is_dir(COMPOSER_PATCHER_TEST_DIRTMP)) {
    mkdir(COMPOSER_PATCHER_TEST_DIRTMP);
}

if (!class_exists('PHPUnit\Runner\Version')) {
    class_alias('PHPUnit_Runner_Version', 'PHPUnit\Runner\Version');
}

if (version_compare(PHPUnit\Runner\Version::id(), '9') >= 0) {
    class_alias('ComposerPatcher\Test\Helpers\TestCase_v3', 'ComposerPatcher\Test\Helpers\TestCase');
} elseif (version_compare(PHPUnit\Runner\Version::id(), '8') >= 0) {
    class_alias('ComposerPatcher\Test\Helpers\TestCase_v2', 'ComposerPatcher\Test\Helpers\TestCase');
} else {
    class_alias('ComposerPatcher\Test\Helpers\TestCase_v1', 'ComposerPatcher\Test\Helpers\TestCase');
}
