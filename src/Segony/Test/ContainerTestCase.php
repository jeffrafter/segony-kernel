<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Test;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @codeCoverageIgnore
 */
abstract class ContainerTestCase extends TestCase
{

    private $container;

    /**
     * @return CotnainerBuilder
     */
    protected function getContainer()
    {
        if (null === $this->container) {
            $this->container = new ContainerBuilder(new ParameterBag([
                'kernel.root_dir'    => $this->getRootDir(),
                'kernel.cache_dir'   => $this->getRootDir() . '/app/cache',
                'kernel.environment' => 'test',
                'kernel.debug'       => false
            ]));

            $this->prepareContainer($this->container);
            $this->container->compile();
        }

        return $this->container;
    }

    abstract protected function prepareContainer($container);
    abstract protected function getRootDir();

}