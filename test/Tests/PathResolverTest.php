<?php

namespace ComposerPatcher\Test\Tests;

use Composer\Config;
use Composer\IO\NullIO;
use Composer\Util\RemoteFilesystem;
use ComposerPatcher\Test\Helpers\TestCase;
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
            new VolatileDirectory(COMPOSER_PATCHER_TEST_DIRTMP),
            new RemoteFilesystem(new NullIO(), new Config())
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
            array(__FILE__, '', str_replace(\DIRECTORY_SEPARATOR, '/', __FILE__)),
            array('file://'.__FILE__, '', str_replace(\DIRECTORY_SEPARATOR, '/', __FILE__)),
            array(basename(__FILE__), __DIR__, str_replace(\DIRECTORY_SEPARATOR, '/', __FILE__)),
            array(basename(__FILE__), 'file://'.__DIR__, str_replace(\DIRECTORY_SEPARATOR, '/', __FILE__)),
        );
    }

    /**
     * @dataProvider resolvePathProvider
     *
     * @param string $path
     * @param string $baseFolder
     * @param string $expected
     */
    public function testResolvePath($path, $baseFolder, $expected)
    {
        $resolved = self::$pathResolver->resolve($path, $baseFolder);
        $this->assertSame($expected, $resolved, "Resolving {$path} relatively to {$baseFolder}");
    }
}
