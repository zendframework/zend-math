<?php
/**
 * @link      http://github.com/zendframework/zend-math for the canonical source repository
 * @copyright Copyright (c) 2005-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Zend\Math;

use ZendTest\Math\RandTest;

function random_bytes($length)
{
    if (RandTest::$custom_random_bytes) {
        throw new \Exception("Random is not supported");
    }
    return \random_bytes($length);
}
