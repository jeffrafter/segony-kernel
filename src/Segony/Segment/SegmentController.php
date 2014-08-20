<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Segment;

use Segony\Layout\Event\LayoutInitializeEvent;
use Segony\Layout\Event\LayoutDispatchEvent;
use Segony\Layout\Event\LayoutRenderEvent;
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
abstract class SegmentController extends Controller implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function getDebugId()
    {
        return sprintf('segment/%s', $this->spy->getEmbeddingKey());
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function getDebugInfo()
    {
        $info = parent::getDebugInfo();
        $info['embeddingKey'] = $this->spy->getEmbeddingKey();

        return $info;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    static public function getSubscribedEvents()
    {
        return [
            ControllerEvent::SITE_PRE_INITIALIZE    => ['onSitePreInitialize'],
            ControllerEvent::SITE_POST_INITIALIZE   => ['onSitePostInitialize'],
            ControllerEvent::SITE_PRE_DISPATCH      => ['onSitePreDispatch'],
            ControllerEvent::SITE_POST_DISPATCH     => ['onSitePostDispatch'],
            ControllerEvent::SITE_PRE_RENDER        => ['onSitePreRender'],
            ControllerEvent::SITE_POST_RENDER       => ['onSitePostRender'],
            ControllerEvent::LAYOUT_PRE_INITIALIZE  => ['onLayoutPreInitialize'],
            ControllerEvent::LAYOUT_POST_INITIALIZE => ['onLayoutPostInitialize'],
            ControllerEvent::LAYOUT_PRE_DISPATCH    => ['onLayoutPreDispatch'],
            ControllerEvent::LAYOUT_POST_DISPATCH   => ['onLayoutPostDispatch'],
            ControllerEvent::LAYOUT_PRE_RENDER      => ['onLayoutPreRender'],
            ControllerEvent::LAYOUT_POST_RENDER     => ['onLayoutPostRender']
        ];
    }

    /**
     * @param  LayoutRenderEvent $event
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
     * @param  LayoutRenderEvent $event
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
     * @param  LayoutInitializeEvent $event
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
     * @param  LayoutInitializeEvent $event
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
     * @param  LayoutDispatchEvent $event
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
     * @param  LayoutDispatchEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onLayoutPostDispatch(LayoutDispatchEvent $event)
    {
    }

    /**
     * @param  SiteInitializeEvent $event
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
     * @param  SiteInitializeEvent $event
     * @return void
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function onSitePostInitialize(SiteInitializeEvent $event)
    {
    }

    /**
     * @param  SiteDispatchEvent $event
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
     * @param  SiteDispatchEvent $event
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