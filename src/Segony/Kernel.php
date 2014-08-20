<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Segony\Config\MainConfigDefinition;
use Segony\Storage\Storage;
use Segony\Kernel\Dispatcher;
use Segony\Debug\Debug;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class Kernel
{

    private $container;
    private $booted = false;
    private $debug = false;
    private $environment;
    private $rootDir;

    /**
     * Constructor
     *
     * @param string  $environment
     * @param boolean $debug
     *
     * @api
     */
    public function __construct($environment, $debug = false)
    {
        $this->environment = $environment;
        $this->debug       = (bool) $debug;
        $this->rootDir     = realpath(__DIR__ . '/../..');
    }

    /**
     * @return array
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function getKernelParameters()
    {
        return [
            'kernel.root_dir'    => $this->rootDir,
            'kernel.cache_dir'   => $this->rootDir . '/app/cache',
            'kernel.environment' => $this->environment,
            'kernel.debug'       => $this->debug
        ];
    }

    /**
     * Boots the kernel
     *
     * @return void
     */
    private function boot()
    {
        if (true === $this->booted) {
            return;
        }

        $this->initializeContainer();

        $this->booted = true;
    }

    /**
     * @return ContainerBuilder
     */
    private function getContainerBuilder()
    {
        $container = new ContainerBuilder(new ParameterBag($this->getKernelParameters()));

        if (class_exists('ProxyManager\Configuration')) {
            $container->setProxyInstantiator(new RuntimeInstantiator());
        }

        return $container;
    }

    /**
     * @return void
     */
    private function initializeContainer()
    {
        $debug = new Debug();

        $debug->start('container.build');
        $container = $this->getContainerBuilder();
        $container->addObjectResource($this);
        $debug->stop('container.build');

        $debug->start('container.initialize_necessary_components');
        $this->initializeNecessaryComponents($container, $debug);
        $debug->stop('container.initialize_necessary_components');

        $this->prepareDebug($container, $debug);

        $debug->start('container.compile');
        $container->compile();
        $debug->stop('container.compile');

        $this->container = $container;
    }

    /**
     * @param  ContainerBuilder $container
     * @param  Debug            $debug
     * @return void
     * @throws \Segony\Exception If the debug memorize bridge does not exists
     */
    private function prepareDebug(ContainerBuilder $container, Debug $debug)
    {
        $config    = $container->get('config')->get('debug')->get('memorize_bridge');
        $className = $config->get('class');
        $options   = $config->get('options', new Storage())->all();

        if (false === class_exists($className)) {
            throw new Exception(sprintf('Cannot find debug memorize bridge class "%s"', $className));
        }

        $reflection = new \ReflectionClass($className);
        $memorizeBridge = $reflection->newInstanceArgs([$options]);
        $memorizeBridge->setContainer($container);

        $debug->setMemorizeBridge($memorizeBridge);

        $container->set('debug', $debug);
    }

    /**
     * @param  ContainerBuilder $container
     * @return void
     */
    private function initializeNecessaryComponents(ContainerBuilder $container, Debug $debug)
    {
        // register the string helper
        $container->register('string_helper', 'Segony\Service\StringHelper');

        // load di service configuration
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../app/config'));
        $loader->load('service.yml');

        // register the configuration loader to get all configs from app/config, app/layout, app/site as well as app/segment
        $container
            ->register('config_loader', 'Segony\Config\ConfigLoader')
            ->addArgument($container);

        // load the main configuration
        $mainConfigFile = ('prod' === $container->getParameter('kernel.environment')) ?
                          'config.yml' : sprintf('config_%s.yml', $container->getParameter('kernel.environment'));
        $mainConfig = $container->get('config_loader')->load($mainConfigFile, new MainConfigDefinition());
        $container->set('config', $mainConfig);

        // register the event dispatcher
        $container->register('event_dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher');

        // register twig
        $container
            ->register('twig_loader_filesystem', 'Twig_Loader_Filesystem')
            ->addArgument($container->getParameter('kernel.root_dir'));

        $container
            ->get('twig_loader_filesystem')
            ->addPath(realpath(__DIR__ . '/Resource'), 'main');

        $container
            ->register('twig_segment_extension', 'Segony\Twig\Extension\SegmentExtension');

        $debug->add($container->get('twig_segment_extension'));

        $container
            ->register('twig', 'Twig_Environment')
            ->addArgument(new Reference('twig_loader_filesystem'))
            ->addArgument($mainConfig->get('twig', new Storage())->all())
            ->addMethodCall('addExtension', [new Reference('twig_segment_extension')]);
    }

    /**
     * @param  Request $request
     * @return Response
     *
     * @api
     */
    public function handle(Request $request)
    {
        $this->boot();

        $this->container->get('debug')->start('kernel.dispatch');

        $dispatcher = new Dispatcher($this->container, $request);
        $response = $dispatcher->dispatch();

        $this->container->get('debug')->stop('kernel.dispatch');

        return $response;
    }

    /**
     * @param  Request  $request
     * @param  Response $response
     * @return void
     *
     * @api
     */
    public function terminate(Request $request, Response $response)
    {
        $this->container
             ->get('debug')
             ->start('kernel.terminate');

        $response->send();

        $this->container
             ->get('debug')
             ->stop('kernel.terminate');

         $this->container
              ->get('debug')
              ->terminate();
    }

}