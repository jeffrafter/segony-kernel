<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Debug\MemorizeBridge;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Segony\Storage\Storage;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
abstract class AbstractMemorizeBridge extends ContainerAware implements MemorizeBridgeInterface
{

    protected $options;

    /**
     * Constructor
     *
     * @param array $options
     *
     * @api
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * @param array $options
     * @return AbstractMemorizeBridge
     *
     * @api
     */
    public function setOptions(array $options)
    {
        $this->options = new Storage($options);

        return $this;
    }

}