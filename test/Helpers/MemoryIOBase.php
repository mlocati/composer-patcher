<?php

namespace ComposerPatcher\Test\Helpers;

use Composer\IO\NullIO;

abstract class MemoryIOBase extends NullIO
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
     * @param string|string[] $messages
     * @param bool $newline
     */
    protected function addLoggedLine($messages, $newline)
    {
        $this->loggedLines .= implode("\n", (array) $messages).($newline ? "\n" : '');
    }
}
