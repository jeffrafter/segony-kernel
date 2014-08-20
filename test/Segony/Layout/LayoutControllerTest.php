<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Layout;

use Segony\Test\SpyTestCase;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Layout\LayoutController
 */
class LayoutEventTest extends SpyTestCase
{

    private $controller;

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../Resource/environment');
    }

    public function testToAddAllLayoutEvents()
    {
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');

        $spy = $this->getSpy('layout', 'valid');
        $spy->hunt();

        $controller = $spy->getBackendController();
        $eventDispatcher->addSubscriber($controller);

        $expectedEvents = array_keys($controller::getSubscribedEvents());
        $currentEvents  = array_keys($eventDispatcher->getListeners());

        foreach ($expectedEvents as $event) {
            $this->assertTrue(in_array($event, $currentEvents), sprintf('%s is available', $event));
        }
    }

}