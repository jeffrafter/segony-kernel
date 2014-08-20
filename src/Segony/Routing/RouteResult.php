<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Routing;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class RouteResult
{

    private $route;
    private $site;
    private $parameters = [];

    public function __construct($site, $route, array $parameters = null)
    {
        $this->route      = $route;
        $this->site       = $site;
        $this->parameters = $parameters;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

}