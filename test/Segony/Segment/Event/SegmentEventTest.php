<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Segment\Event;

use Segony\Test\SegmentSpyTestCase;
use Segony\Segment\Event\SegmentInitializeEvent;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Segment\Event\AbstractSegmentEvent
 * @covers \Segony\Segment\Event\SegmentDispatchEvent
 * @covers \Segony\Segment\Event\SegmentInitializeEvent
 * @covers \Segony\Segment\Event\SegmentRenderEvent
 */
class SegmentEventTest extends SegmentSpyTestCase
{

    private $event;

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../../Resource/environment');
    }

    public function setUp()
    {
        $spy = $this->getSegmentSpy('segment', 'valid', 'just_a_test');
        $spy->hunt();

        $this->event = new SegmentInitializeEvent(
            $spy->getEmbeddingKey(),
            $spy->getName(),
            $spy->getBackendController()
        );
    }

    public function testToGetTheEmbeddingKey()
    {
        $this->assertSame('just_a_test', $this->event->getEmbeddingKey());
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