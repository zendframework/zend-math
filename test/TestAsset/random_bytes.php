<?php
/**
 * @link      http://github.com/zendframework/zend-math for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Zend\Math;

use Exception;
use ZendTest\Math\RandTest;

/**
 * Generate random bytes with $length size or throw an Exception,
 * to test a PHP platform without secure random number generator installed
 *
 * @param int $length
 * @return string
 */
function random_bytes($length)
{
    if (RandTest::$customRandomBytes) {
        throw new Exception("Random is not supported");
    }
    return \random_bytes($length);
}
