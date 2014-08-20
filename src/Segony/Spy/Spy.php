<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Spy;

use Segony\Storage\Storage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
abstract class Spy implements SpyInterface
{

    protected $name;
    protected $innerName;
    protected $container;
    protected $config;
    protected $view;
    protected $backendController;
    protected $clientController;

    private $basePath;
    private $type;

    /**
     * Constructor
     *
     * @param string             $name
     * @param ContainerInterface $container
     *
     * @api
     */
    public function __construct($name, ContainerInterface $container)
    {
        if (false === is_string($name)) {
            throw new SpyException('Invalid spy name obtained');
        }

        $this->name      = ('/' === substr($name, 0, 1)) ? substr($name, 1) : $name;
        $this->container = $container;

        $stack           = explode('/', $name);
        $this->innerName = array_pop($stack);
    }

    /**
     * @return string
     *
     * @api
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     *
     * @api
     */
    public function getInnerName()
    {
        return $this->innerName;
    }

    /**
     * @return string
     *
     * @api
     */
    public function getType()
    {
        if (null === $this->type) {
            $stack = explode('\\', get_class($this));
            $raw   = array_pop($stack);

            $this->type = strtolower(str_replace('Spy', '', $raw));
        }

        return $this->type;
    }

    /**
     * Returns the component base path as a string using the "kernel.root_dir" as well as the obtained name.
     *
     * @return string
     *
     * @api
     */
    protected function getBasePath()
    {
        if (null === $this->basePath) {
            $this->basePath = $this->container->getParameter('kernel.root_dir') . '/app/' . $this->getType() . '/' . $this->getName();
        }

        return $this->basePath;
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
     * @return Storage
     *
     * @api
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Controller
     *
     * @api
     */
    public function getBackendController()
    {
        return $this->backendController;
    }

    /**
     * @return string
     *
     * @api
     */
    public function getClientController()
    {
        return $this->clientController;
    }

    /**
     * @return void
     */
    protected function initConfig()
    {
        $configPath = $this->getBasePath() . '/' . $this->getInnerName() . '.yml';

        if (false === file_exists($configPath)) {
            throw new SpyException(sprintf('Cannot find main config file for the %s "%s"', $this->getType(), $this->name));
        }

        $this->config = $this->container->get('config_loader')->load($configPath);
    }

    /**
     * @return string
     */
    protected function getBackendNamespace()
    {
        $sh    = $this->container->get('string_helper');
        $ns    = [ucfirst($this->getType())];
        $stack = explode('/', $this->getName());

        foreach ($stack as $part) {
            array_push($ns, $sh->camelCasify($part));
        }

        return implode('\\', $ns);
    }

    /**
     * @return void
     */
    protected function initBackendController()
    {
        $sh       = $this->container->get('string_helper');
        $filePath = $this->getBasePath() . '/' . $sh->camelCasify($this->getInnerName()) .
                    ucfirst($this->getType()) . 'Controller.php';

        if (true === file_exists($filePath)) {
            require_once $filePath;

            try {
                $reflection = new \ReflectionClass(
                    $this->getBackendNamespace() . '\\' . $sh->camelCasify($this->getInnerName()) . ucfirst($this->getType()) . 'Controller'
                );

                $this->backendController = $reflection->newInstanceArgs([$this, $this->container]);
                $this->container->get('event_dispatcher')->addSubscriber($this->backendController);
            } catch (\ReflectionException $e) {
                throw new SpyException(
                    sprintf('Invalid %s backend controller definition for "%s"', $this->getType(), $this->getInnerName()),
                    null,
                    $e
                );
            }
        }
    }

    /**
     * @return void
     */
    protected function initClientController()
    {
        $clientControllerPath = $this->getBasePath() . '/' . $this->getInnerName() . '.js';
        $this->clientController = false;

        if (true === file_exists($clientControllerPath)) {
            $this->clientController = $this->getType() . '/' . $this->getName();
        }
    }

    /**
     * @return void
     */
    protected function initView()
    {
        $viewPath = $this->getBasePath() . '/' . (('segment' === $this->getType()) ? 'view/' : '') .
                    $this->getInnerName() . '.html.twig';

        if (false === file_exists($viewPath)) {
            throw new SpyException(sprintf('Cannot find view file for the %s "%s"', $this->getType(), $this->name));
        }

        $this->view = $this->getInnerName() . '.html.twig';
    }

    /**
     * @return void
     *
     * @api
     */
    final public function hunt()
    {
        $rc = new \ReflectionClass($this);

        foreach ($rc->getMethods() as $method) {
            if (true === $method->isProtected() && 'init' === substr($method->getName(), 0, 4)) {
                $this->{$method->getName()}();
            }
        }
    }

}