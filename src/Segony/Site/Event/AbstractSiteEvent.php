<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Site\Event;

use Symfony\Component\EventDispatcher\Event;
use Segony\Controller\Controller;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
abstract class AbstractSiteEvent extends Event
{

    private $id;
    private $controller;

    /**
     * Constructor
     *
     * @param string     $id
     * @param Controller $controller
     *
     * @api
     */
    public function __construct($id, Controller $controller)
    {
        $this->id         = $id;
        $this->controller = $controller;
    }

    /**
     * @return string
     *
     * @api
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Controller
     *
     * @api
     */
    public function getController()
    {
        return $this->controller;
    }

}