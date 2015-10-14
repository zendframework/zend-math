<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Math\BigInteger;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * Plugin manager implementation for BigInteger adapters.
 *
 * Enforces that adapters retrieved are instances of
 * Adapter\AdapterInterface. Additionally, it registers a number of default
 * adapters available.
 */
class AdapterPluginManager extends AbstractPluginManager
{
    protected $aliases = [
        'bcmath' => Adapter\Bcmath::class,
        'Bcmath' => Adapter\Bcmath::class,
        'gmp'    => Adapter\Gmp::class,
        'Gmp'    => Adapter\Gmp::class
    ];

    protected $factories = [
        Adapter\Bcmath::class => InvokableFactory::class,
        Adapter\Gmp::class    => InvokableFactory::class
    ];

    /**
     * Validate the plugin
     *
     * Checks that the adapter loaded is an instance of Adapter\AdapterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Adapter\AdapterInterface) {
            // we're okay
            return;
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Adapter\AdapterInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}
