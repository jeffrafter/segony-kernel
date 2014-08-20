<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Loader;

use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class YamlFileLoader extends FileLoader
{

    private $yamlParser;
    private $parameterBagMap;

    public function load($resource, $type = null)
    {
        $file    = $this->locator->locate($resource);
        $content = $this->loadFile($file);

        if (null === $content) {
            return;
        }

        $content = $this->parseImportStatement($content, $file);

        // add parameters if available
        if (array_key_exists('parameters', $content)) {
            foreach ($content['parameters'] as $key => $value) {
                $this->container->setParameter($key, $value);
            }

            unset($content['parameters']);
        }

        return $content;
    }

    private function parseImportStatement($content, $file)
    {
        if (false === isset($content['import'])) {
            return $content;
        }

        while (count($content['import']) > 0) {
            $import = array_shift($content['import']);

            $data = $this->loadFile($this->locator->locate($import['resource']));
            $content = array_merge($content, $data);
        }

        unset($content['import']);

        return $content;
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }

    private function loadFile($file)
    {
        if (true === function_exists('yaml_parse')) {
            return yaml_parse(file_get_contents($file));
        }

        if (null === $this->yamlParser) {
            $this->yamlParser = new YamlParser();
        }

        return $this->yamlParser->parse(file_get_contents($file));
    }

}