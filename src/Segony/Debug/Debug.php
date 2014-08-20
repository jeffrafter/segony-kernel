<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Debug;

use Segony\Exception;
use Segony\Debug\MemorizeBridge\MemorizeBridgeInterface;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class Debug implements DebugInterface
{

    private $id;
    private $sequences = [];
    private $debuggableItems = [];
    private $memorizeBridge;

    /**
     * Constructor
     *
     * @param MemorizeBridgeInterface|null $memorizeBridge
     *
     * @api
     */
    public function __construct(MemorizeBridgeInterface $memorizeBridge = null)
    {
        $this->id = uniqid();

        if (null !== $memorizeBridge) {
            $this->setMemorizeBridge($memorizeBridge);
        }
    }

    /**
     * @param MemorizeBridgeInterface $memorizeBridge
     * @return Debug
     *
     * @api
     */
    public function setMemorizeBridge(MemorizeBridgeInterface $memorizeBridge)
    {
        $this->memorizeBridge = $memorizeBridge;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function start($id, array $data = null)
    {
        $this->sequences[$id] = new Sequence($id);

        if (null !== $data) {
            $this->sequences[$id]->capture($data);
        }

        return $this->sequences[$id];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function stop($id, array $data = null)
    {
        if ($id instanceof Sequence) {
            $sequence = $id;
        } else {
            if (false === array_key_exists($id, $this->sequences)) {
                throw new Exception(sprintf('Cannot find debug sequence with the identifier "%s"', $id));
            }

            $sequence = $this->sequences[$id];
        }

        if (null !== $data) {
            $sequence->capture($data);
        }

        $sequence->stop();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function add(Debuggable $item)
    {
        if (array_key_exists($item->getDebugId(), $this->debuggableItems)) {
            throw new Exception(sprintf('There is already a debug item with the identifier :id', $item->getDebugId()));
        }

        $info = $item->getDebugInfo();

        if (false === is_array($info)) {
            throw new Exception('Invalid debug information (expect an array)');
        }

        $this->debuggableItems[$item->getDebugId()] = $info;

        return $this;
    }

    /**
     * Exports the obtained data
     *
     * @return array
     *
     * @api
     */
    public function all()
    {
        $data = [
            'sequences' => [],
            'debuggableItems' => $this->debuggableItems
        ];

        foreach ($this->sequences as $id => $sequence) {
            $data['sequences'][$id] = $sequence->all();
        }

        return $data;
    }

    /**
     * Terminates the debugger. This function will also trigger the memorize bridge to store the data.
     *
     * @return void
     *
     * @api
     */
    final public function terminate()
    {
        $this->memorizeBridge->save($this->getId(), $this->all());
    }

}