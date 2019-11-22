<?php

namespace ComposerPatcher\Test;

abstract class TestCase_v2 extends TestCaseBase
{
    final public static function setUpBeforeClass(): void
    {
        static::mySetUpBeforeClass();
    }

    final public static function tearDownAfterClass(): void
    {
        static::myTearDownAfterClass();
    }
}
