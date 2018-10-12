<?php

namespace ComposerPatcher\PatchExecutor;

use Composer\IO\IOInterface;
use Composer\Util\ProcessExecutor;
use ComposerPatcher\Exception;
use ComposerPatcher\Patch;
use ComposerPatcher\PatchExecutor;

class GitPatcher extends PatchExecutor
{
    /**
     * Initialize the instance.
     *
     * @param \Composer\Util\ProcessExecutor $processExecutor the ProcessExecutor instance to be used to actually run commands
     * @param \Composer\IO\IOInterface $io the IOInterface instance to be used for user feedback
     *
     * @throws \ComposerPatcher\Exception\CommandNotFound when the git command is not available
     */
    public function __construct(ProcessExecutor $processExecutor, IOInterface $io)
    {
        parent::__construct($processExecutor, $io);
        $this->checkCommandExists('git');
    }

    /**
     * Check if this PatchExecutor class should be used to apply patches to a directory.
     *
     * @param string $baseDirectory
     *
     * @return bool
     */
    public static function shouldBeUsetToApplyPatchesTo($baseDirectory)
    {
        return is_dir("${baseDirectory}/.git");
    }

    /**
     * {@inheritdoc}
     *
     * @see \ComposerPatcher\PatchExecutor::applyPatchLevel()
     */
    protected function applyPatchLevel(Patch $patch, $baseDirectory, $patchLevel)
    {
        foreach (array(true, false) as $justTesting) {
            $command = $this->buildCommand($patch->getLocalPath(), $baseDirectory, $patchLevel, $justTesting);
            if ($justTesting && $this->io->isVerbose()) {
                $this->io->write("<comment>Testing ability to patch with \"git apply\" using patch level {$patchLevel} with the following command:\n{$command}</comment>");
            }
            list($rc, , $stdErr) = $this->run($command);
            if ($rc !== 0) {
                throw new Exception\PatchNotApplied($patch, "failed to apply the patch with GIT: {$stdErr}");
            }
            if (strpos($stdErr, 'Skipped') === 0) {
                return;
            }
        }
    }

    /**
     * @param string $localPatchFile
     * @param string $baseDirectory
     * @param string $patchLevel
     * @param bool $justTesting
     *
     * @return string
     */
    private function buildCommand($localPatchFile, $baseDirectory, $patchLevel, $justTesting)
    {
        $chunks = array(
            'git',
            // Run git as if it was started in $baseDirectory
            '-C ', $this->processExecutor->escape(str_replace('/', DIRECTORY_SEPARATOR, $baseDirectory)),
            'apply',
            $this->processExecutor->escape($patchLevel),
        );
        if ($justTesting) {
            $chunks[] = '--check';
            $chunks[] = '-v';
        }
        $chunks[] = $this->processExecutor->escape(str_replace('/', DIRECTORY_SEPARATOR, $localPatchFile));

        return implode(' ', $chunks);
    }
}
