<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Segment;

use Segony\Controller\Controller;
use Segony\Exception;
use Segony\Spy\SegmentSpy;
use Segony\Storage\Storage;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class SegmentWorker extends ContainerAware
{

    private $registry = [];
    private $information = [];

    /**
     * Register a new segment
     *
     * @param  string $id
     * @param  Storage $definition
     * @return SegmentWorker
     *
     * @api
     */
    public function register($id, Storage $definition)
    {
        if (true === array_key_exists($id, $this->registry)) {
            throw new Exception(sprintf('There is already a segment called "%s"', $id));
        }

        $this->registry[$id] = $definition;

        return $this;
    }

    /**
     * Prioritises the registrated segments
     *
     * @return array
     *
     * @api
     */
    protected function getPrioritizedRegistry()
    {
        $prioritized = [];

        foreach ($this->registry as $id => $definition) {
            $valuation = (int) $definition->get('priority', 1);

            if (false === array_key_exists($valuation, $prioritized)) {
                $prioritized[$valuation] = [];
            }

            $prioritized[$valuation][$id] = $definition;
        }

        krsort($prioritized);
        return $prioritized;
    }

    /**
     * @return array
     *
     * @api
     */
    public function process()
    {
        $response = [];

        foreach ($this->getPrioritizedRegistry() as $priority => $stack) {
            foreach ($stack as $id => $definition) {
                $ss = new SegmentSpy($id, $definition->get('segment'), $this->container);
                $ss->hunt();

                $controller = $ss->getBackendController();

                $controller->handle(Controller::MODE_INITIALIZE, [
                    $definition->get('config', new Storage()),
                    $definition->get('view', null)
                ]);

                $controller->handle(Controller::MODE_DISPATCH);

                $response[$id] = sprintf(
                    '<div class="segment" id="%s" data-segment="%s">%s</div>',
                    $id,
                    $definition->get('segment'),
                    $controller->handle(Controller::MODE_RENDER)
                );

                $this->information[$id] = [
                    'embeddingKey' => $ss->getEmbeddingKey(),
                    'controller'   => $ss->getClientController(),
                    'config'       => $ss->getBackendController()->getConfig()->all()
                ];

                $this->container->get('debug')->add($controller);
            }
        }

        return $response;
    }

    /**
     * @return array
     *
     * @api
     */
    public function getInformation()
    {
        return $this->information;
    }

}