<?php

namespace ComposerPatcher\Test;

abstract class TestCase_v1 extends TestCaseBase
{
    final public static function setUpBeforeClass()
    {
        static::mySetUpBeforeClass();
    }

    final public static function tearDownAfterClass()
    {
        static::myTearDownAfterClass();
    }
}
