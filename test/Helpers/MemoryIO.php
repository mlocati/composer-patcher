<?php

namespace ComposerPatcher\Test\Helpers;

use Composer\IO\NullIO;

class MemoryIO extends NullIO
{
    private $loggedLines = '';

    /**
     * @return string
     */
    public function getLoggedLines()
    {
        return $this->loggedLines;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Composer\IO\IOInterface::write()
     */
    public function write($messages, $newline = true, $verbosity = self::NORMAL)
    {
        $this->addLoggedLine($messages, $newline);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Composer\IO\IOInterface::writeError()
     */
    public function writeError($messages, $newline = true, $verbosity = self::NORMAL)
    {
        $this->addLoggedLine($messages, $newline);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Composer\IO\IOInterface::overwrite()
     */
    public function overwrite($messages, $newline = true, $size = 80, $verbosity = self::NORMAL)
    {
        $this->addLoggedLine($messages, $newline);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Composer\IO\IOInterface::overwriteError()
     */
    public function overwriteError($messages, $newline = true, $size = 80, $verbosity = self::NORMAL)
    {
        $this->addLoggedLine($messages, $newline);
    }

    /**
     * @param string|string[] $messages
     * @param bool $newline
     */
    private function addLoggedLine($messages, $newline)
    {
        $this->loggedLines .= implode("\n", (array) $messages).($newline ? "\n" : '');
    }
}
