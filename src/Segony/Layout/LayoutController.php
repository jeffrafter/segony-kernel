<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Layout;

use Segony\Segment\Event\SegmentInitializeEvent;
use Segony\Segment\Event\SegmentDispatchEvent;
use Segony\Segment\Event\SegmentRenderEvent;
use Segony\Site\Event\SiteInitializeEvent;
use Segony\Site\Event\SiteDispatchEvent;
use Segony\Site\Event\SiteRenderEvent;
use Segony\Controller\Controller;
use Segony\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
abstract class LayoutController extends Controller implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     */
    static public function getSubscribedEvents()
    {
        return [
            ControllerEvent::SITE_PRE_INITIALIZE     => ['onSitePreInitialize'],
            ControllerEvent::SITE_POST_INITIALIZE    => ['onSiteInitializePost'],
            ControllerEvent::SITE_PRE_DISPATCH       => ['onSitePreDispatch'],
            ControllerEvent::SITE_POST_DISPATCH      => ['onSitePostDispatch'],
            ControllerEvent::SITE_PRE_RENDER         => ['onSitePreRender'],
            ControllerEvent::SITE_POST_RENDER        => ['onSitePostRender'],
            ControllerEvent::SEGMENT_PRE_INITIALIZE  => ['onSegmentPreInitialize'],
            ControllerEvent::SEGMENT_POST_INITIALIZE => ['onSegmentPostInitialize'],
            ControllerEvent::SEGMENT_PRE_DISPATCH    => ['onSegmentPreDispatch'],
            ControllerEvent::SEGMENT_POST_DISPATCH   => ['onSegmentPostDispatch'],
            ControllerEvent::SEGMENT_PRE_RENDER      => ['onSegmentPreRender'],
            ControllerEvent::SEGMENT_POST_RENDER     => ['onSegmentPostRender']
        ];
    }

    /**
     * @param  SiteRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSitePreRender(SiteRenderEvent $event)
    {
    }

    /**
     * @param  SiteRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSitePostRender(SiteRenderEvent $event)
    {
    }

    /**
     * @param  SiteRenderEvent $event
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
     * @param  SiteRenderEvent $event
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
     * @param  SiteRenderEvent $event
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
     * @param  SiteRenderEvent $event
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
     * @param  SiteRenderEvent $event
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
     * @param  SiteRenderEvent $event
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
     * @param  SiteRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSitePreInitialize(SiteInitializeEvent $event)
    {
    }

    /**
     * @param  SiteRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSiteInitializePost(SiteInitializeEvent $event)
    {
    }

    /**
     * @param  SiteRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSitePreDispatch(SiteDispatchEvent $event)
    {
    }

    /**
     * @param  SiteRenderEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSitePostDispatch(SiteDispatchEvent $event)
    {
    }

}