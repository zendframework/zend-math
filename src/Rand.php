<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Math;

/**
 * Pseudorandom number generator (PRNG)
 */
abstract class Rand
{
    /**
     * Generate random bytes using different approaches
     * If PHP 7 is running we use the random_bytes() function
     *
     * @param  int $length
     * @return string
     * @throws Exception\RuntimeException
     */
    public static function getBytes($length)
    {
        try {
            return random_bytes($length);
        } catch (\TypeError $e) {
            throw new Exception\InvalidArgumentException(
                'Invalid parameter provided to getBytes(length)'
            );
        } catch (\Error $e) {
            throw new Exception\DomainException(
                'The length must be a positive number in getBytes(length)'
            );
        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                'This PHP environment doesn\'t support secure random number generation. ' .
                'Please consider upgrading to PHP 7'
            );
        }
    }

    /**
     * Generate random boolean
     *
     * @return bool
     */
    public static function getBoolean()
    {
        $byte = static::getBytes(1);
        return (bool) (ord($byte) % 2);
    }

    /**
     * Generate a random integer between $min and $max
     *
     * @param  int $min
     * @param  int $max
     * @return int
     * @throws Exception\DomainException
     */
    public static function getInteger($min, $max)
    {
        try {
            return random_int($min, $max);
        } catch (\TypeError $e) {
            throw new Exception\InvalidArgumentException(
                'Invalid parameters provided to getInteger(min, max)'
            );
        } catch (\Error $e) {
            throw new Exception\DomainException(
                'The min parameter must be lower than max in getInteger(min, max)'
            );
        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                'This PHP environment doesn\'t support secure random number generation. ' .
                'Please consider upgrading to PHP 7'
            );
        }
    }

    /**
     * Generate random float (0..1)
     * This function generates floats with platform-dependent precision
     *
     * PHP uses double precision floating-point format (64-bit) which has
     * 52-bits of significand precision. We gather 7 bytes of random data,
     * and we fix the exponent to the bias (1023). In this way we generate
     * a float of 1.mantissa.
     *
     * @return float
     */
    public static function getFloat()
    {
        $bytes    = static::getBytes(7);
        $bytes[6] = $bytes[6] | chr(0xF0);
        $bytes   .= chr(63); // exponent bias (1023)
        list(, $float) = unpack('d', $bytes);

        return ($float - 1);
    }

    /**
     * Generate a random string of specified length.
     *
     * Uses supplied character list for generating the new string.
     * If no character list provided - uses Base 64 character set.
     *
     * @param  int $length
     * @param  string|null $charlist
     * @return string
     * @throws Exception\DomainException
     */
    public static function getString($length, $charlist = null)
    {
        if ($length < 1) {
            throw new Exception\DomainException('Length should be >= 1');
        }

        // charlist is empty or not provided
        if (empty($charlist)) {
            $numBytes = ceil($length * 0.75);
            $bytes    = static::getBytes($numBytes);
            return mb_substr(rtrim(base64_encode($bytes), '='), 0, $length, '8bit');
        }

        $listLen = mb_strlen($charlist, '8bit');

        if ($listLen == 1) {
            return str_repeat($charlist, $length);
        }

        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $pos     = static::getInteger(0, $listLen - 1);
            $result .= $charlist[$pos];
        }
        return $result;
    }
}
