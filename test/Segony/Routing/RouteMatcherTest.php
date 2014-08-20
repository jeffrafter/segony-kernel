<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Storage;

use Segony\Routing\RouteMatcher;
use Segony\Test\ContainerTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Routing\RouteMatcher
 * @covers \Segony\Routing\RouteResult
 *
 * @runTestsInSeparateProcesses
 */
class RouteMatcherTest extends ContainerTestCase
{

    protected function prepareContainer($container)
    {
        $container
            ->register('config_loader', 'Segony\Config\ConfigLoader')
            ->addArgument($container);

        $container
            ->register('string_helper', 'Segony\Service\StringHelper');

        $container
            ->register('event_dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher');

        $container->set('config', $container->get('config_loader')->load('config_test.yml'));
    }

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../Resource/environment');
    }

    private function getRouteMatcher(Request $request = null)
    {
        return new RouteMatcher($this->getContainer(), $request);
    }

    public function testToMatchWithoutRequest()
    {
        $this->setExpectedException('Exception');
        $this->getRouteMatcher();
    }

    public function testToMatchUndefinedRoute()
    {
        $_SERVER['REQUEST_URI'] = '/not-found';
        $request = Request::createFromGlobals();

        $matcher = $this->getRouteMatcher($request);
        $result = $matcher->match();

        $this->assertNull($result);
    }

    public function testFileSystemRoute()
    {
        $_SERVER['REQUEST_URI'] = '/valid';
        $request = Request::createFromGlobals();

        $matcher = $this->getRouteMatcher($request);
        $result = $matcher->match();

        $this->assertInstanceOf('Segony\Routing\RouteResult', $result);
    }

    public function testToMatchDefinedRouteAsWellAsTheRoutingResult()
    {
        $request = Request::createFromGlobals();

        $matcher = $this->getRouteMatcher($request);
        $result = $matcher->match('/test/by/phpunit');

        $this->assertInstanceOf('Segony\Routing\RouteResult', $result);

        $this->assertSame('/valid', $result->getSite());
        $this->assertSame('test', $result->getRoute());
        $this->assertSame([], $result->getParameters());
    }

}