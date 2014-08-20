<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Segony\Debug\Debuggable;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class DebuggableItem implements Debuggable
{

    public function getDebugId()
    {
        return 'test';
    }

    public function getDebugInfo()
    {
        return [
            'firstname' => 'Jon',
            'lastname'  => 'Doe'
        ];
    }

}