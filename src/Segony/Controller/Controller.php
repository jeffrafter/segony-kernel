<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Segony\Debug\Debuggable;
use Segony\Spy\SpyInterface;
use Segony\Spy\SegmentSpy;
use Segony\Storage\Storage;
use Segony\Exception;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
abstract class Controller extends Storage implements ContainerAwareInterface, Debuggable
{

    const MODE_INITIALIZE = 'initialize';
    const MODE_DISPATCH = 'dispatch';
    const MODE_RENDER = 'render';

    protected $spy;
    private $container;
    private $startTime;
    private $config;
    private $view;

    /**
     * Constructor
     *
     * @param SpyInterface       $spy
     * @param ContainerInterface $container
     *
     * @api
     */
    public function __construct(SpyInterface $spy, ContainerInterface $container)
    {
        $this->spy       = $spy;
        $this->startTime = microtime(true);

        $this->setContainer($container);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param  string $name
     * @return mixed
     *
     * @api
     */
    public function getService($name)
    {
        return $this->container->get($name);
    }

    /**
     * @return Request
     *
     * @api
     */
    public function getRequest()
    {
        return $this->container->get('request');
    }

    /**
     * @return Storage
     *
     * @api
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return string
     *
     * @api
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param  string $name
     * @return Event
     */
    private function getEvent($name)
    {
        $type = ucfirst($this->spy->getType());
        $args = [$this->spy->getName(), $this->spy->getBackendController()];

        if ($this->spy instanceof SegmentSpy) {
            $args = [$this->spy->getEmbeddingKey(), $this->spy->getName(), $this->spy->getBackendController()];
        }

        $class = new \ReflectionClass(implode('\\', ['Segony', $type, 'Event', sprintf('%s%sEvent', $type, ucfirst($name))]));
        return $class->newInstanceArgs($args);
    }

    /**
     * @param  string $name
     * @return string
     */
    private function callEvent($name)
    {
        return $this->container
                    ->get('event_dispatcher')
                    ->dispatch(sprintf('%s.%s', $this->spy->getType(), $name), $this->getEvent(
                        str_replace(['pre_', 'post_'], ['', ''], $name)
                    ));
    }

    /**
     * @param  Storage|null $config
     * @param  string       $view
     * @return void
     */
    private function runInitialize(Storage $config = null, $view = null)
    {
        $this->config = $this->spy->getConfig();
        $this->view   = $this->spy->getView();

        if (null !== $config && $this->spy->getConfig() instanceof ConfigurationInterface) {
            $definitionProcessor = new Processor();
            $config = $definitionProcessor->processConfiguration(
                $this->spy->getConfig(),
                [$config->all()]
            );

            $this->config = new Storage($config);
        }

        if (true === is_string($view)) {
            $availableViews = $this->spy->getAvailableViews()->all();

            if (false === in_array($view, $availableViews)) {
                throw new Exception(sprintf(
                    'The segment "%s" cannot find the view "%s" - select between %s',
                    $this->spy->getName(), $view, implode(', ', $availableViews)
                ));
            }

            $this->view = $view;
        }
    }

    /**
     * @return void
     */
    private function runDispatch()
    {
    }

    /**
     * @param  array|null $additionalData
     * @return string
     */
    private function runRender(array $additionalData = null)
    {
        $viewPath = '/app/' . $this->spy->getType() . '/' . $this->spy->getName();

        if ('segment' === $this->spy->getType()) {
            $viewPath .= '/view';
        }

        $viewPath .= '/' . $this->getView();

        if (true === $this->has('config')) {
            throw new Exception(sprintf('Cannot use reserved key "config" (%s:%s)', $this->spy->getType(), $this->spy->getName()));
        }

        if (true === $this->has('segment')) {
            throw new Exception(sprintf('Cannot use reserved key "segment" (%s:%s)', $this->spy->getType(), $this->spy->getName()));
        }

        $data = array_merge($this->all(), ['config' => $this->getConfig()->all()]);

        if (null !== $additionalData) {
            $data = array_merge($data, $additionalData);
        }

        return $this->getService('twig')->render($viewPath, $data);
    }

    /**
     * @see    Symfony\Component\EventDispatcher\EventDispatcher::addListener()
     * @param  string   $event
     * @param  callable $callback
     * @return void
     *
     * @api
     */
    final public function on($event, callable $callback)
    {
        $this->container->get('event_dispatcher')->addListener($event, $callback);
    }

    /**
     * @param  string $mode
     * @param  array  $args
     * @return string
     *
     * @api
     */
    final public function handle($mode = self::MODE_INITIALIZE, array $args = [])
    {
        if (false === in_array($mode, [self::MODE_INITIALIZE, self::MODE_DISPATCH, self::MODE_RENDER])) {
            throw new Exception('Invalid mode obtained');
        }

        $this->callEvent(sprintf('pre_%s', $mode));
        $result = call_user_func_array([$this, sprintf('run%s', ucfirst($mode))], $args);

        if (true === method_exists($this, $mode)) {
            call_user_func([$this, $mode]);
        }

        $this->callEvent(sprintf('post_%s', $mode));

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function getDebugId()
    {
        return sprintf('%s/%s', $this->spy->getType(), $this->spy->getName());
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function getDebugInfo()
    {
        return [
            'id'       => $this->spy->getName(),
            'type'     => $this->spy->getType(),
            'duration' => round((microtime(true) - $this->startTime) * 1000, 2),
            'config'   => $this->getConfig(),
            'view'     => $this->getView(),
            'data'     => $this->all()
        ];
    }

}