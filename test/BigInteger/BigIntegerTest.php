<?php
/**
 * @link      http://github.com/zendframework/zend-math for the canonical source repository
 * @copyright Copyright (c) 2005-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Math\BigInteger;

use PHPUnit\Framework\TestCase;
use Zend\Math\BigInteger\Adapter\AdapterInterface;
use Zend\Math\BigInteger\Adapter\Bcmath;
use Zend\Math\BigInteger\BigInteger as BigInt;

class BigIntegerTest extends TestCase
{
    public function testFactoryCreatesBcmathAdapter()
    {
        if (! extension_loaded('bcmath')) {
            $this->markTestSkipped('Missing bcmath extensions');
        }

        $bigInt = BigInt::factory('Bcmath');
        $this->assertInstanceOf('Zend\Math\BigInteger\Adapter\Bcmath', $bigInt);
    }

    public function testFactoryCreatesGmpAdapter()
    {
        if (! extension_loaded('gmp')) {
            $this->markTestSkipped('Missing gmp extensions');
        }

        $bigInt = BigInt::factory('Gmp');
        $this->assertInstanceOf('Zend\Math\BigInteger\Adapter\Gmp', $bigInt);
    }

    public function testFactoryUsesDefaultAdapter()
    {
        if (! extension_loaded('bcmath') && ! extension_loaded('gmp')) {
            $this->markTestSkipped('Missing bcmath or gmp extensions');
        }
        $this->assertInstanceOf('Zend\Math\BigInteger\Adapter\AdapterInterface', BigInt::factory());
    }

    public function testFactoryUnknownAdapterRaisesException()
    {
        $this->expectException('Zend\Math\Exception\ExceptionInterface');
        BigInt::factory('unknown');
    }

    public function testSetDefaultAdapter()
    {
        if (! extension_loaded('bcmath')) {
            $this->markTestSkipped('Missing bcmath extensions');
        }

        BigInt::setDefaultAdapter('bcmath');
        $this->assertInstanceOf(AdapterInterface::class, BigInt::getDefaultAdapter());
        $this->assertInstanceOf(Bcmath::class, BigInt::getDefaultAdapter());
    }

    /**
     * @covers Zend\Math\BigInteger\BigInteger::__callStatic
     */
    public function testCallStatic()
    {
        if (! extension_loaded('bcmath')) {
            $this->markTestSkipped('Missing bcmath extensions');
        }
        BigInt::setDefaultAdapter('bcmath');
        $result = BigInt::add(1, 2);
        $this->assertEquals(3, $result);
    }
}
