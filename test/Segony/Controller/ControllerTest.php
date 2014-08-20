<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Controller;

use Segony\Test\SpyTestCase;
use Segony\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Controller\Controller
 */
class ControllerTest extends SpyTestCase
{

    private $controller;

    public function setUp()
    {
        $spy = $this->getSpy('site', 'valid');
        $spy->hunt();

        $this->controller = $spy->getBackendController();
    }

    public function tearDown()
    {
        $this->controller = null;
    }

    protected function prepareContainer($container)
    {
        $container->set('request', Request::createFromGlobals());

        parent::prepareContainer($container);
    }

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../Resource/environment');
    }

    public function testToGetTheCurrentRequest()
    {
        $request = $this->controller->getRequest();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $request);
    }

    public function testToInitializeTheContainer()
    {
        $preInitializeTriggered  = false;
        $postInitializeTriggered = false;

        $this->controller->on('site.pre_initialize', function () use (&$preInitializeTriggered) {
            $preInitializeTriggered = true;
        });

        $this->controller->on('site.post_initialize', function () use (&$postInitializeTriggered) {
            $postInitializeTriggered = true;
        });

        $this->controller->handle(Controller::MODE_INITIALIZE);

        $this->assertTrue($preInitializeTriggered, 'The event "site.pre_initialize"');
        $this->assertTrue($postInitializeTriggered, 'The event "site.post_initialize"');
        $this->assertTrue($this->controller->publicInitializeFunctionTriggered);
    }

    public function testToDispatchInvalidMode()
    {
        $this->setExpectedException('Segony\Exception');
        $this->controller->handle('something');
    }

    public function testThatViewAndConfigIsNullBeforeInitialize()
    {
        $this->assertEmpty(is_string($this->controller->getView()));
        $this->assertEmpty($this->controller->getConfig());
    }

    public function testThatViewAndConfigIsNotNullAfterInitialize()
    {
        $this->controller->handle(Controller::MODE_INITIALIZE);

        $this->assertTrue(is_string($this->controller->getView()));
        $this->assertInstanceOf('Segony\Storage\Storage', $this->controller->getConfig());
    }

    public function testToGetService()
    {
        $this->assertInstanceOf('Segony\Service\StringHelper', $this->controller->getService('string_helper'));
    }

}