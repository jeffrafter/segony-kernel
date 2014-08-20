<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Site\Event;

use Segony\Test\SpyTestCase;
use Segony\Site\Event\SiteInitializeEvent;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Site\Event\AbstractSiteEvent
 * @covers \Segony\Site\Event\SiteDispatchEvent
 * @covers \Segony\Site\Event\SiteInitializeEvent
 * @covers \Segony\Site\Event\SiteRenderEvent
 */
class SiteEventTest extends SpyTestCase
{

    private $event;

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../../Resource/environment');
    }

    public function setUp()
    {
        $spy = $this->getSpy('site', 'valid');
        $spy->hunt();

        $this->event = new SiteInitializeEvent(
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