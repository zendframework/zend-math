<?php
/**
 * @link      http://github.com/zendframework/zend-math for the canonical source repository
 * @copyright Copyright (c) 2005-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZendTest\Math;

use Exception;
use PHPUnit\Framework\TestCase;
use Zend\Math\Rand;

class RandTest extends TestCase
{
    public static $custom_random_bytes = false;

    public function tearDown()
    {
        self::$custom_random_bytes = false;
    }

    public static function provideRandInt()
    {
        return [
            [2, 1, 10000, 100, 0.9, 1.1],
            [2, 1, 10000, 100, 0.8, 1.2]
        ];
    }

    public function testRandBytes()
    {
        for ($length = 1; $length < 4096; $length++) {
            $rand = Rand::getBytes($length);
            $this->assertNotFalse($rand);
            $this->assertEquals($length, mb_strlen($rand, '8bit'));
        }
    }

    public function testWrongRandBytesParam()
    {
        $this->expectException('Zend\Math\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('Invalid parameter provided to getBytes(length)');
        Rand::getBytes('foo');
    }

    public function testZeroRandBytesParam()
    {
        $this->expectException('Zend\Math\Exception\DomainException');
        $this->expectExceptionMessage('The length must be a positive number in getBytes(length)');
        Rand::getBytes(0);
    }

    public function testNegativeRandBytesParam()
    {
        $this->expectException('Zend\Math\Exception\DomainException');
        $this->expectExceptionMessage('The length must be a positive number in getBytes(length)');
        Rand::getBytes(-1);
    }

    public function testUnsupportedPlatform()
    {
        self::$custom_random_bytes = true;
        $this->expectException('Zend\Math\Exception\RuntimeException');
        $rand = Rand::getBytes(2);
    }

    public function testRandBoolean()
    {
        for ($length = 1; $length < 512; $length++) {
            $rand = Rand::getBoolean();
            $this->assertInternalType('bool', $rand);
        }
    }

    /**
     * @dataProvider dataProviderForTestRandIntegerRangeTest
     */
    public function testRandIntegerRangeTest($min, $max, $cycles)
    {
        $counter = [];
        for ($i = $min; $i <= $max; $i++) {
            $counter[$i] = 0;
        }

        for ($j = 0; $j < $cycles; $j++) {
            $value = Rand::getInteger($min, $max);
            $this->assertInternalType('integer', $value);
            $this->assertGreaterThanOrEqual($min, $value);
            $this->assertLessThanOrEqual($max, $value);
            $counter[$value]++;
        }

        foreach ($counter as $value => $count) {
            $this->assertGreaterThan(0, $count, sprintf('The bucket for value %d is empty.', $value));
        }
    }

    /**
     * @return array
     */
    public function dataProviderForTestRandIntegerRangeTest()
    {
        return [
            [0, 100, 10000],
            [-100, 100, 10000],
            [-100, 50, 10000],
            [0, 63, 10000],
            [0, 64, 10000],
            [0, 65, 10000],
        ];
    }

    /**
     * A Monte Carlo test that generates $cycles numbers from 0 to $tot
     * and test if the numbers are above or below the line y=x with a
     * frequency range of [$min, $max]
     *
     * @dataProvider provideRandInt
     */
    public function testRandInteger($num, $valid, $cycles, $tot, $min, $max)
    {
        try {
            $test = Rand::getBytes(1);
        } catch (\Zend\Math\Exception\RuntimeException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        $i     = 0;
        $count = 0;
        do {
            $up   = 0;
            $down = 0;
            for ($i = 0; $i < $cycles; $i++) {
                $x = Rand::getInteger(0, $tot);
                $y = Rand::getInteger(0, $tot);
                if ($x > $y) {
                    $up++;
                } elseif ($x < $y) {
                    $down++;
                }
            }
            $this->assertGreaterThan(0, $up);
            $this->assertGreaterThan(0, $down);
            $ratio = $up / $down;
            if ($ratio > $min && $ratio < $max) {
                $count++;
            }
            $i++;
        } while ($i < $num && $count < $valid);

        if ($count < $valid) {
            $this->fail('The random number generator failed the Monte Carlo test');
        }
    }

    public function testWrongFirstParamGetInteger()
    {
        $this->expectException('Zend\Math\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('Invalid parameters provided to getInteger(min, max)');
        Rand::getInteger('foo', 0);
    }

    public function testWrongSecondParamGetInteger()
    {
        $this->expectException('Zend\Math\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('Invalid parameters provided to getInteger(min, max)');
        Rand::getInteger(0, 'foo');
    }

    public function testIntegerRangeFail()
    {
        $this->expectException('Zend\Math\Exception\DomainException');
        $this->expectExceptionMessage('The min parameter must be lower than max in getInteger(min, max)');
        Rand::getInteger(100, 0);
    }

    public function testIntegerRangeOverflow()
    {
        $values = 0;
        $cycles = 100;
        for ($i = 0; $i < $cycles; $i++) {
            $values += Rand::getInteger(0, PHP_INT_MAX);
        }

        // It's not possible to test $values > 0 because $values may suffer a integer overflow
        $this->assertNotEquals(0, $values);
    }

    public function testRandFloat()
    {
        for ($length = 1; $length < 512; $length++) {
            $rand = Rand::getFloat();
            $this->assertInternalType('float', $rand);
            $this->assertGreaterThanOrEqual(0, $rand);
            $this->assertLessThanOrEqual(1, $rand);
        }
    }

    public function testGetString()
    {
        for ($length = 1; $length < 512; $length++) {
            $rand = Rand::getString($length, '0123456789abcdef');
            $this->assertEquals(strlen($rand), $length);
            $this->assertEquals(1, preg_match('#^[0-9a-f]+$#', $rand));
        }
    }

    public function testGetStringBase64()
    {
        for ($length = 1; $length < 512; $length++) {
            $rand = Rand::getString($length);
            $this->assertEquals(strlen($rand), $length);
            $this->assertEquals(1, preg_match('#^[0-9a-zA-Z+/]+$#', $rand));
        }
    }

    public function testGetNegativeSizeStringExpectException()
    {
        $this->expectException('Zend\Math\Exception\DomainException');
        $rand = Rand::getString(-1);
    }

    public function testGetStringWithOneCharacter()
    {
        for ($length = 1; $length < 512; $length++) {
            $rand = Rand::getString($length, 'a');
            $this->assertEquals(strlen($rand), $length);
            $this->assertEquals(str_repeat('a', $length), $rand);
        }
    }
}
