<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Debug;

use Segony\Storage\Storage;
use Segony\Storage\ReservedStorageInterface;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class Sequence extends Storage implements ReservedStorageInterface
{

    /**
     * Constructor
     *
     * @param string $id
     *
     * @api
     */
    public function __construct($id)
    {
        $this->offsetSet('id', $id);
        $this->offsetSet('startTime', microtime(true));
    }

    /**
     * Stops the sequence
     *
     * @return void
     *
     * @api
     */
    public function stop()
    {
        $this->offsetSet('endTime', microtime(true));
        $this->offsetSet(
            'duration',
            round(($this->offsetGet('endTime') - $this->offsetGet('startTime')) * 1000, 2)
        );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getReservedKeys()
    {
        return ['duration', 'id', 'startTime', 'stopTime'];
    }

}