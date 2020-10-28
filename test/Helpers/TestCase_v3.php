<?php

namespace ComposerPatcher\Test\Helpers;

abstract class TestCase_v3 extends TestCaseBase
{
    /**
     * {@inheritdoc}
     *
     * @see \PHPUnit\Framework\TestCase::setUpBeforeClass()
     */
    final public static function setUpBeforeClass(): void
    {
        static::mySetUpBeforeClass();
    }

    /**
     * {@inheritdoc}
     *
     * @see \PHPUnit\Framework\TestCase::tearDownAfterClass()
     */
    final public static function tearDownAfterClass(): void
    {
        static::myTearDownAfterClass();
    }

    /**
     * {@inheritdoc}
     *
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    final protected function setUp(): void
    {
        $this->mySetUp();
    }

    /**
     * {@inheritdoc}
     *
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    final protected function tearDown(): void
    {
        $this->myTearDown();
    }

    protected static function myAssertMatchesRegularExpression(string $pattern, string $string, string $message = ''): void
    {
        static::assertMatchesRegularExpression(...\func_get_args());
    }

    protected static function myAssertDoesNotMatchRegularExpression(string $pattern, string $string, string $message = ''): void
    {
        static::assertDoesNotMatchRegularExpression(...\func_get_args());
    }

    protected static function myAssertFileDoesNotExist(string $filename, string $message = ''): void
    {
        static::assertFileDoesNotExist(...\func_get_args());
    }
}
