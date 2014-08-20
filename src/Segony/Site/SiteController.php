<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Site;

use Segony\Layout\Event\LayoutInitializeEvent;
use Segony\Layout\Event\LayoutDispatchEvent;
use Segony\Layout\Event\LayoutRenderEvent;
use Segony\Segment\Event\SegmentInitializeEvent;
use Segony\Segment\Event\SegmentDispatchEvent;
use Segony\Segment\Event\SegmentRenderEvent;
use Segony\Controller\Controller;
use Segony\Segment\Event\PrepareEvent;
use Segony\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
abstract class SiteController extends Controller implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     */
    static public function getSubscribedEvents()
    {
        return [
            ControllerEvent::SEGMENT_PRE_INITIALIZE  => ['onSegmentPreInitialize'],
            ControllerEvent::SEGMENT_POST_INITIALIZE => ['onSegmentPostInitialize'],
            ControllerEvent::SEGMENT_PRE_DISPATCH    => ['onSegmentPreDispatch'],
            ControllerEvent::SEGMENT_POST_DISPATCH   => ['onSegmentPostDispatch'],
            ControllerEvent::SEGMENT_PRE_RENDER      => ['onSegmentPreRender'],
            ControllerEvent::SEGMENT_POST_RENDER     => ['onSegmentPostRender'],
            ControllerEvent::LAYOUT_PRE_INITIALIZE   => ['onLayoutPreInitialize'],
            ControllerEvent::LAYOUT_POST_INITIALIZE  => ['onLayoutPostInitialize'],
            ControllerEvent::LAYOUT_PRE_DISPATCH     => ['onLayoutPreDispatch'],
            ControllerEvent::LAYOUT_POST_DISPATCH    => ['onLayoutPostDispatch'],
            ControllerEvent::LAYOUT_PRE_RENDER       => ['onLayoutPreRender'],
            ControllerEvent::LAYOUT_POST_RENDER      => ['onLayoutPostRender']
        ];
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSegmentPreRender(SegmentRenderEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSegmentPostRender(SegmentRenderEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onLayoutPreRender(LayoutRenderEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onLayoutPostRender(LayoutRenderEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSegmentPreInitialize(SegmentInitializeEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSegmentPostInitialize(SegmentInitializeEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSegmentPreDispatch(SegmentDispatchEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSegmentPostDispatch(SegmentDispatchEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onLayoutPreInitialize(LayoutInitializeEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onLayoutPostInitialize(LayoutInitializeEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onLayoutPreDispatch(LayoutDispatchEvent $event)
    {
    }

    /**
     * @param  SegmentRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onLayoutPostDispatch(LayoutDispatchEvent $event)
    {
    }

}