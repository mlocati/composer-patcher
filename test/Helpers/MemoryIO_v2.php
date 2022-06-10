<?php

namespace ComposerPatcher\Test\Helpers;

class MemoryIO_v2 extends MemoryIOBase
{
    /**
     * {@inheritdoc}
     *
     * @see \Composer\IO\IOInterface::write()
     */
    public function write($messages, bool $newline = true, int $verbosity = self::NORMAL): void
    {
        $this->addLoggedLine($messages, $newline);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Composer\IO\IOInterface::writeError()
     */
    public function writeError($messages, bool $newline = true, int $verbosity = self::NORMAL): void
    {
        $this->addLoggedLine($messages, $newline);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Composer\IO\IOInterface::overwrite()
     */
    public function overwrite($messages, bool $newline = true, ?int $size = null, int $verbosity = self::NORMAL): void
    {
        $this->addLoggedLine($messages, $newline);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Composer\IO\IOInterface::overwriteError()
     */
    public function overwriteError($messages, bool $newline = true, ?int $size = null, int $verbosity = self::NORMAL): void
    {
        $this->addLoggedLine($messages, $newline);
    }
}
