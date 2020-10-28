<?php

namespace ComposerPatcher\Test\Tests;

use Composer\Composer;
use Composer\Factory;
use Composer\Installer;
use ComposerPatcher\Test\Helpers\MemoryIO;
use ComposerPatcher\Test\Helpers\TestCase;
use ComposerPatcher\Util\VolatileDirectory;

class PatchingTest extends TestCase
{
    /**
     * @var \ComposerPatcher\Util\VolatileDirectory|null
     */
    private $volatileDirectoryTemp;

    /**
     * @var \ComposerPatcher\Util\VolatileDirectory|null
     */
    private $volatileDirectory;

    /**
     * @var string|null
     */
    private $initialDir;

    /**
     * @return array[]
     */
    public function feedPatching()
    {
        return array(
            array(false),
            array(true),
        );
    }

    /**
     * @dataProvider feedPatching
     *
     * @param bool $allowSubpatches
     */
    public function testPatching($allowSubpatches)
    {
        $this->volatileDirectory = $this->createProject($allowSubpatches);
        chdir($this->volatileDirectory->getPath());
        for ($cycle = 1; $cycle <= ($allowSubpatches ? 2 : 1); $cycle++) {
            $io = new MemoryIO();
            $composer = Factory::create($io, null, false);
            $installer = Installer::create($io, $composer)->setUpdate(true);
            $rc = $installer->run();
            $loggedLines = trim($io->getLoggedLines());
            $this->assertSame(0, $rc, "composer update failed at cycle #{$cycle}.\nOutput:\n{$loggedLines}");
            $patchedFileContents = file_get_contents($this->volatileDirectory->getPath().'//patchme.txt');
            if ($allowSubpatches) {
                $this->assertFalse(strpos($loggedLines, 'No patches supplied') !== false, "Patches should be applied, but output doesn't state that at cycle #{$cycle}.\nOutput:\n{$loggedLines}");
                switch ($cycle) {
                    case 1:
                        $this->myAssertMatchesRegularExpression('/Patching file [^\r\n]+\bdone\b/', $loggedLines, $loggedLines);
                        $this->assertFalse(strpos($loggedLines, 'patch was already applied') !== false, $loggedLines);
                        break;
                    case 2:
                        $this->myAssertDoesNotMatchRegularExpression('/Patching file [^\r\n]+\bdone\b/', $loggedLines, $loggedLines);
                        $this->assertTrue(strpos($loggedLines, 'patch was already applied') !== false, $loggedLines);
                        break;
                }
                $this->assertTrue(strpos($patchedFileContents, 'Really useful!') !== false, 'File has not been patched!');
                $this->assertFalse(strpos($patchedFileContents, 'Quite useless.') !== false, 'File has been wrongly patched!');
            } else {
                $this->assertTrue(strpos($loggedLines, 'No patches supplied') !== false, "No patches should be applied, but output doesn't state that.\nOutput:\n{$loggedLines}");
                $this->assertTrue(strpos($patchedFileContents, 'Quite useless.') !== false, 'File has been patched!');
                $this->assertFalse(strpos($patchedFileContents, 'Really useful!') !== false, 'File has been patched!');
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \ComposerPatcher\Test\Helpers\TestCaseBase::mySetUp()
     */
    protected function mySetUp()
    {
        $this->initialDir = getcwd();
        $this->volatileDirectoryTemp = new VolatileDirectory(COMPOSER_PATCHER_TEST_DIRTMP);
    }

    /**
     * {@inheritdoc}
     *
     * @see \ComposerPatcher\Test\Helpers\TestCaseBase::myTearDown()
     */
    protected function myTearDown()
    {
        chdir($this->initialDir);
        $this->volatileDirectory = null;
        $this->volatileDirectoryTemp = null;
    }

    /**
     * @param bool $allowSubpatches
     *
     * @return \ComposerPatcher\Util\VolatileDirectory
     */
    protected function createProject($allowSubpatches)
    {
        $composerJson = array(
            'name' => 'mlocati/composer-patcher-sample-project',
            'description' => 'Test project',
            'type' => 'project',
            'license' => 'MIT',
            'require' => array(
                'mlocati/composer-patcher-sample-patcher' => '*',
            ),
            'minimum-stability' => 'dev',
            'repositories' => array(
                array(
                    'packagist.org' => false,
                ),
                array(
                    'type' => 'path',
                    'url' => COMPOSER_PATCHER_TEST_DIRROOT,
                ),
                array(
                    'type' => 'path',
                    'url' => COMPOSER_PATCHER_TEST_DIRTEST.'/assets/sample_patcher',
                ),
            ),
            'extra' => array(
                'patch-errors-as-warnings' => false,
                'patch-temporary-folder' => $this->volatileDirectoryTemp->getPath(),
            ),
        );
        if ($allowSubpatches) {
            $composerJson['extra'] += array(
                'allow-subpatches' => array(
                    'mlocati/composer-patcher-sample-patcher',
                ),
            );
        }
        $vDir = new VolatileDirectory(version_compare(Composer::RUNTIME_API_VERSION, '2') ? sys_get_temp_dir() : COMPOSER_PATCHER_TEST_DIRTMP);
        file_put_contents(
            $vDir->getPath().'/composer.json',
            json_encode(
                $composerJson,
                0 | (\defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0) | (\defined('JSON_UNESCAPED_SLASHES') ? JSON_UNESCAPED_SLASHES : 0)
            )
        );
        file_put_contents($vDir->getPath().'/patchme.txt', <<<'EOT'
This is a sample file
with some sample content.
Quite useless.
EOT
        );

        return $vDir;
    }
}
