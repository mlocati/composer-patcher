<?php

namespace ComposerPatcher\Test\Helpers;

class MemoryIO_v1 extends MemoryIOBase
{
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
}
