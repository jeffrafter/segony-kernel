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
abstract class SegmentSpyTestCase extends SpyTestCase
{

    private $spy = [];

    /**
     * @param  string $type
     * @param  string $name
     * @return Spy
     */
    protected function getSegmentSpy($type, $name, $embeddingKey)
    {
        $key = $type . $name . $embeddingKey;

        if (false === array_key_exists($key, $this->spy)) {
            $reflection = new \ReflectionClass(sprintf('Segony\Spy\%sSpy', ucfirst($type)));
            $this->spy[$key] = $reflection->newInstanceArgs([$embeddingKey, $name, $this->getContainer()]);
        }

        return $this->spy[$key];
    }

}