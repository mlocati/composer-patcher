<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

define('COMPOSER_PATCHER_TEST_TMP', __DIR__.'/tmp');
if (!is_dir(COMPOSER_PATCHER_TEST_TMP)) {
    mkdir(COMPOSER_PATCHER_TEST_TMP);
}
