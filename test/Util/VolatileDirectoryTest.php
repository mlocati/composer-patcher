<?php

namespace ComposerPatcher\Test\Util;

use ComposerPatcher\Test\TestCase;
use ComposerPatcher\Util\VolatileDirectory;

class VolatileDirectoryTest extends TestCase
{
    /**
     * @return array
     */
    public function invalidParentDirectoryProvider()
    {
        return array(
            array(__DIR__.'/non-existing-directory'),
        );
    }

    /**
     * @dataProvider invalidParentDirectoryProvider
     *
     * @param string $parentDirectory
     */
    public function testInvalidParentDirectory($parentDirectory)
    {
        $this->myExpectException('ComposerPatcher\Exception\PathNotFound');
        $vd = new VolatileDirectory($parentDirectory);
        $vd->getNewPath();
    }

    public function testDirectoryDeleted()
    {
        $vd = new VolatileDirectory(COMPOSER_PATCHER_TEST_TMP);
        $dir = $vd->getPath();
        $this->assertFileExists($dir);
        touch($vd->getNewPath());
        mkdir($vd->getNewPath());
        unset($vd);
        $this->assertFileNotExists($dir);
    }
}
