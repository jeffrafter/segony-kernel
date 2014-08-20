<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Debug;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
interface DebugInterface
{

    /**
     * @return string
     *
     * @api
     */
    public function getId();

    /**
     * @param  string     $id
     * @param  array|null $data
     * @return Sequence
     *
     * @api
     */
    public function start($id, array $data = null);

    /**
     * @param  string|Sequence $id
     * @param  array|null      $data
     * @return Debug
     *
     * @api
     */
    public function stop($id, array $data = null);

    /**
     * @param  Debuggable $item
     * @return Debug
     *
     * @api
     */
    public function add(Debuggable $item);

}