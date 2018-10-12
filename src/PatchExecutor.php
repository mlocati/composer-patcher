<?php

namespace ComposerPatcher;

use Composer\IO\IOInterface;
use Composer\Util\ProcessExecutor;

abstract class PatchExecutor
{
    /**
     * The IOInterface instance to be used for user feedback.
     *
     * @var \Composer\IO\IOInterface
     */
    protected $io;
    /**
     * The ProcessExecutor instance to be used to actually run commands.
     *
     * @var \Composer\Util\ProcessExecutor
     */
    protected $processExecutor;

    /**
     * Initialize the instance.
     *
     * @param \Composer\Util\ProcessExecutor $processExecutor the ProcessExecutor instance to be used to actually run commands
     * @param \Composer\IO\IOInterface $io the IOInterface instance to be used for user feedback
     */
    protected function __construct(ProcessExecutor $processExecutor, IOInterface $io)
    {
        $this->processExecutor = $processExecutor;
        $this->io = $io;
    }

    /**
     * Apply a patch to a base directory.
     *
     * @param \ComposerPatcher\Patch $patch the patch to be applied
     * @param string $baseDirectory the directory where the patch should be applied
     *
     * @return bool
     */
    public function applyPatch(Patch $patch, $baseDirectory)
    {
        $error = null;
        foreach ($patch->getLevels() as $patchLevel) {
            try {
                $this->applyPatchLevel($patch, $baseDirectory, $patchLevel);

                return;
            } catch (Exception\PatchNotApplied $x) {
                if ($error === null) {
                    $error = $x;
                }
            }
        }
        throw $error;
    }

    /**
     * Apply a patch using a specific patch level to a base directory.
     *
     * @param \ComposerPatcher\Patch $patch the patch to be applied
     * @param string $baseDirectory the directory where the patch should be applied
     * @param string $patchLevel the patch level
     *
     * @throws \ComposerPatcher\Exception\PatchNotApplied when the command failed
     */
    abstract protected function applyPatchLevel(Patch $patch, $baseDirectory, $patchLevel);

    /**
     * Run a command and return the exit code.
     *
     * @param string $command The command to be executed
     * @param null|mixed $standardInput
     *
     * @return array First element is the command return code, second element is the standard output, thirg element is the standard error
     */
    protected function run($command, $standardInput = null)
    {
        $stdOut = '';
        $stdErr = '';
        $stdOut = array();
        $rc = $this->processExecutor->execute($command, $stdOut);
        $stdOut = (string) $stdOut;
        $stdErr = (string) $this->processExecutor->getErrorOutput();

        return array($rc, $stdOut, $stdErr);
    }

    /**
     * Check if a command exists.
     *
     * @param string $commandName the command name
     * @param string $argument the argument to be used to run the command
     *
     * @throws \ComposerPatcher\Exception\CommandNotFound
     */
    protected function checkCommandExists($commandName, $argument = '--version')
    {
        $command = $commandName;
        $argument = (string) $argument;
        if ($argument !== '') {
            $command .= ' '.$argument;
        }
        list($rc, , $stdErr) = $this->run($command);
        if ($rc !== 0) {
            throw new Exception\CommandNotFound($commandName, $stdErr ? $stdErr : null);
        }
    }
}
