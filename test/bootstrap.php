<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

define('COMPOSER_PATCHER_TEST_TMP', __DIR__.'/tmp');
if (!is_dir(COMPOSER_PATCHER_TEST_TMP)) {
    mkdir(COMPOSER_PATCHER_TEST_TMP);
}

if (!class_exists('PHPUnit\Runner\Version')) {
    class_alias('PHPUnit_Runner_Version', 'PHPUnit\Runner\Version');
}

if (version_compare(PHPUnit\Runner\Version::id(), '8') >= 0) {
    class_alias('ComposerPatcher\Test\TestCase_v2', 'ComposerPatcher\Test\TestCase');
} else {
    class_alias('ComposerPatcher\Test\TestCase_v1', 'ComposerPatcher\Test\TestCase');
}
