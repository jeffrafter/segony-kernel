<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Routing;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class RouteMatcher
{

    private $matcher;
    private $context;
    private $container;

    public function __construct(ContainerInterface $container, Request $request)
    {
        $collection = new RouteCollection();

        if ($container->get('config')->has('routing')) {
            foreach ($container->get('config')->get('routing') as $id => $item) {
                $route = new Route($item->get('pattern'), $item->all());
                $collection->add($id, $route);
            }
        }

        $context = new RequestContext();
        $context->fromRequest($request);

        $this->matcher   = new UrlMatcher($collection, $context);
        $this->context   = $context;
        $this->container = $container;
    }

    public function match($path = null)
    {
        if (null === $path) {
            $path = $this->context->getPathInfo();
        }

        $info     = ($path === '/') ? '/index' : $path;
        $expected = $this->container->getParameter('kernel.root_dir') . '/app/site/' .
                    ((substr($info, 0, 1) === '/') ? substr($info, 1) : $info);

        if (is_dir($expected)) {
            return new RouteResult(null, $info);
        }

        try {
            $found = $this->matcher->match($info);

            $site  = $found['_site'];
            $route = $found['_route'];

            unset($found['pattern']);
            unset($found['_site']);
            unset($found['_route']);

            return new RouteResult($site, $route, $found);
        } catch (ResourceNotFoundException $e) {
            return null;
        }
    }

}