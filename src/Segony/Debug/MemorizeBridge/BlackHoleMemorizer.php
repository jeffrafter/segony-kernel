<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Debug\MemorizeBridge;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @codeCoverageIgnore
 */
class BlackHoleMemorizer extends AbstractMemorizeBridge
{

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function save($id, array $data = null)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function load($id)
    {
    }

}