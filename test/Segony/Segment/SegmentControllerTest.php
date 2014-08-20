<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Segment;

use Segony\Test\SegmentSpyTestCase;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Segment\SegmentController
 */
class SegmentEventTest extends SegmentSpyTestCase
{

    private $controller;

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../Resource/environment');
    }

    public function testToAddAllSegmentEvents()
    {
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');

        $spy = $this->getSegmentSpy('segment', 'valid', 'just_a_key');
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