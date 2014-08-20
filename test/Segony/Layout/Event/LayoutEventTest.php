<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Layout\Event;

use Segony\Test\SpyTestCase;
use Segony\Layout\Event\LayoutInitializeEvent;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Layout\Event\AbstractLayoutEvent
 * @covers \Segony\Layout\Event\LayoutDispatchEvent
 * @covers \Segony\Layout\Event\LayoutInitializeEvent
 * @covers \Segony\Layout\Event\LayoutRenderEvent
 */
class LayoutEventTest extends SpyTestCase
{

    private $event;

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../../Resource/environment');
    }

    public function setUp()
    {
        $spy = $this->getSpy('layout', 'valid');
        $spy->hunt();

        $this->event = new LayoutInitializeEvent(
            $spy->getName(),
            $spy->getBackendController()
        );
    }

    public function testToGetTheId()
    {
        $this->assertSame('valid', $this->event->getId());
    }

    public function testToGetTheController()
    {
        $this->assertInstanceOf('Segony\Controller\Controller', $this->event->getController());
    }

}