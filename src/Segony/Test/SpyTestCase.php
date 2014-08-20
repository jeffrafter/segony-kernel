<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Test;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @codeCoverageIgnore
 */
abstract class SpyTestCase extends ContainerTestCase
{

    private $spy = [];

    /**
     * @param  string $type
     * @param  string $name
     * @return Spy
     */
    protected function getSpy($type, $name)
    {
        $key = $type . $name;

        if (false === array_key_exists($key, $this->spy)) {
            $reflection = new \ReflectionClass(sprintf('Segony\Spy\%sSpy', ucfirst($type)));
            $this->spy[$key] = $reflection->newInstanceArgs([$name, $this->getContainer()]);
        }

        return $this->spy[$key];
    }

    /**
     * @param  ContainerBuilder $container
     * @return void
     */
    protected function prepareContainer($container)
    {
        $container
            ->register('config_loader', 'Segony\Config\ConfigLoader')
            ->addArgument($container);

        $container
            ->register('string_helper', 'Segony\Service\StringHelper');

        $container
            ->register('event_dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher');
    }

}