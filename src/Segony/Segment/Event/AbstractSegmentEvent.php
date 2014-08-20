<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Segment\Event;

use Symfony\Component\EventDispatcher\Event;
use Segony\Controller\Controller;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
abstract class AbstractSegmentEvent extends Event
{

    private $embeddingKey;
    private $id;
    private $controller;

    public function __construct($embeddingKey, $id, Controller $controller)
    {
        $this->embeddingKey = $embeddingKey;
        $this->id           = $id;
        $this->controller   = $controller;
    }

    public function getEmbeddingKey()
    {
        return $this->embeddingKey;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getController()
    {
        return $this->controller;
    }

}