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
class SegmentSpy extends Spy
{

    private $embeddingKey;
    private $availableViews = [];

    /**
     * Constructor
     *
     * @param string             $embeddingKey
     * @param string             $name
     * @param ContainerInterface $container
     *
     * @api
     */
    public function __construct($embeddingKey, $name, ContainerInterface $container)
    {
        if (false === is_string($embeddingKey)) {
            throw new SpyException(sprintf('Invalid embedding key "%s" obtained', $embeddingKey));
        }

        $this->embeddingKey = $embeddingKey;
        parent::__construct($name, $container);
    }

    /**
     * @return string
     *
     * @api
     */
    public function getEmbeddingKey()
    {
        return $this->embeddingKey;
    }

    /**
     * @return array
     *
     * @api
     */
    public function getAvailableViews()
    {
        return $this->availableViews;
    }

    /**
     * @return void
     */
    protected function initAvailableViews()
    {
        $viewPath = $this->getBasePath() . '/view';

        if (false === is_dir($viewPath)) {
            throw new SpyException(sprintf('Invalid view directory "%s', $viewPath));
        }

        $iterator = new \DirectoryIterator($viewPath);

        foreach ($iterator as $view) {
            if (true === $view->isDot()) {
                continue;
            }

            array_push($this->availableViews, $view->getFilename());
        }

        $this->availableViews = new Storage($this->availableViews);
    }

    /**
     * @return void
     */
    protected function initConfig()
    {
        $stringHelper    = $this->container->get('string_helper');
        $treeBuilderFile = $this->getBasePath() . '/' . $stringHelper->camelCasify($this->getInnerName()) .
                           ucfirst($this->getType()) . 'Config.php';

        if (true === file_exists($treeBuilderFile)) {
            require_once $treeBuilderFile;

            $class = $this->getBackendNamespace() . '\\' . $stringHelper->camelCasify($this->getInnerName()) . ucfirst($this->getType()) . 'Config';
            $this->config = new $class($this, $this->container);

            return;
        }

        parent::initConfig();
    }

}