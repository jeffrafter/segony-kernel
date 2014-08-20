<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Storage;

/**
 * Sometimes it is indispensable to reserve required keys. Just use this interface
 * and implement the method getReservedKeys() to get it work.
 *
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
interface ReservedStorageInterface
{

    /**
     * @return array
     */
    public function getReservedKeys();

}