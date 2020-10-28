<?php

namespace ComposerPatcher\Test\Helpers;

abstract class TestCase_v1 extends TestCaseBase
{
    /**
     * {@inheritdoc}
     *
     * @see \PHPUnit\Framework\TestCase::setUpBeforeClass()
     */
    final public static function setUpBeforeClass()
    {
        static::mySetUpBeforeClass();
    }

    /**
     * {@inheritdoc}
     *
     * @see \PHPUnit\Framework\TestCase::tearDownAfterClass()
     */
    final public static function tearDownAfterClass()
    {
        static::myTearDownAfterClass();
    }

    /**
     * {@inheritdoc}
     *
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    final protected function setUp()
    {
        $this->mySetUp();
    }

    /**
     * {@inheritdoc}
     *
     * @see \PHPUnit\Framework\TestCase::tearDown()
     */
    final protected function tearDown()
    {
        $this->myTearDown();
    }

    protected static function myAssertMatchesRegularExpression($pattern, $string, $message = '')
    {
        static::assertRegExp($pattern, $string, $message);
    }

    protected static function myAssertDoesNotMatchRegularExpression($pattern, $string, $message = '')
    {
        static::assertNotRegExp($pattern, $string, $message);
    }

    protected static function myAssertFileDoesNotExist($filename, $message = '')
    {
        static::assertFileNotExists($filename, $message);
    }
}
