<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Config;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Segony\Loader\YamlFileLoader;
use Segony\Storage\Storage;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Config\ConfigCache;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class ConfigLoader
{

    private $delegatingLoader;
    private $container;
    private $cache = [];

    /**
     * Constructor
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;

        $fileLocator = new FileLocator([
            $this->container->getParameter('kernel.root_dir') . '/app/config',
            $this->container->getParameter('kernel.root_dir') . '/app/segment',
            $this->container->getParameter('kernel.root_dir') . '/app/site',
            $this->container->getParameter('kernel.root_dir') . '/app/layout'
        ]);

        $loaderResolver = new LoaderResolver(array(new YamlFileLoader($container, $fileLocator)));
        $this->delegatingLoader = new DelegatingLoader($loaderResolver);
    }

    /**
     * @param  array  $config
     * @param  array  $parameters
     * @return array
     */
    private function applyParameters(array $config, array $parameters)
    {
        $search  = [];
        $replace = [];

        foreach ($parameters as $key => $value) {
            array_push($search, '%' . $key . '%');
            array_push($replace, $value);
        }

        return $this->replaceParameters($config, $search, $replace);
    }

    /**
     * @param  array  $config
     * @param  array  $search
     * @param  array  $replace
     * @return array
     */
    private function replaceParameters(array $config, array $search, array $replace)
    {
        foreach ($config as $key => $value) {
            if (true === is_array($value)) {
                $config[$key] = $this->replaceParameters($value, $search, $replace);
                continue;
            }

            if (is_string($value)) {
                $config[$key] = str_replace($search, $replace, $value);
            }
        }

        return $config;
    }

    /**
     * @param  string $resource
     * @return string
     */
    private function getCacheFile($resource)
    {
        return $this->container->get('string_helper')->slugify($resource);
    }

    /**
     * @param  string $file
     * @return string
     */
    private function getCache($file)
    {
        if (false === array_key_exists($file, $this->cache)) {
            $this->cache = new ConfigCache($file, true);
        }

        return $this->cache;
    }

    /**
     * Loads a resource
     *
     * @param  string $resource
     * @return Storage
     *
     * @api
     */
    public function load($resource, ConfigurationInterface $definition = null)
    {
        $file  = $this->getCacheFile($resource);
        $path  = $this->container->getParameter('kernel.cache_dir') . '/config/' . $file;
        $cache = $this->getCache($path);

        if (false === $cache->isFresh()) {
            $data = $this->delegatingLoader->load($resource) ?: [];

            if (null !== $definition) {
                $definitionProcessor = new Processor();
                $data = $definitionProcessor->processConfiguration($definition, [$data]);
            }

            $data = $this->applyParameters($data, $this->container->getParameterBag()->all());
            $cache->write(json_encode($data), [new FileResource($resource)]);
        }

        $data = file_get_contents($path);
        $data = json_decode($data, true);

        return new Storage($data);
    }

}