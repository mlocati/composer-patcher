<?php

namespace ComposerPatcher\Test\Util;

use Composer\IO\NullIO;
use Composer\Util\RemoteFilesystem;
use ComposerPatcher\Test\TestCase;
use ComposerPatcher\Util\PathResolver;
use ComposerPatcher\Util\VolatileDirectory;

class PathResolverTest extends TestCase
{
    /**
     * @var \ComposerPatcher\Util\PathResolver
     */
    private static $pathResolver;

    public static function mySetUpBeforeClass()
    {
        parent::mySetUpBeforeClass();
        self::$pathResolver = new PathResolver(
            new VolatileDirectory(COMPOSER_PATCHER_TEST_TMP),
            new RemoteFilesystem(new NullIO())
        );
    }

    public static function myTearDownAfterClass()
    {
        self::$pathResolver = null;
        parent::myTearDownAfterClass();
    }

    public function resolvePathProvider()
    {
        return array(
            array('', '', ''),
            array(__FILE__, '', str_replace(DIRECTORY_SEPARATOR, '/', __FILE__)),
            array('file://'.__FILE__, '', str_replace(DIRECTORY_SEPARATOR, '/', __FILE__)),
            array(basename(__FILE__), __DIR__, str_replace(DIRECTORY_SEPARATOR, '/', __FILE__)),
            array(basename(__FILE__), 'file://'.__DIR__, str_replace(DIRECTORY_SEPARATOR, '/', __FILE__)),
        );
    }

    /**
     * @dataProvider resolvePathProvider
     *
     * @param mixed $path
     * @param mixed $baseFolder
     * @param mixed $expected
     */
    public function testResolvePath($path, $baseFolder, $expected)
    {
        $resolved = self::$pathResolver->resolve($path, $baseFolder);
        $this->assertSame($expected, $resolved, "Resolving {$path} relatively to {$baseFolder}");
    }
}
