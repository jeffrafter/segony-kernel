<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Event;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
final class ControllerEvent
{

    /**
     * The site.pre_initialize event is thrown each time a site gets initialized.
     *
     * The event listener receives an
     * Segony\Site\Event\SiteInitializeEvent
     *
     * @var string
     *
     * @api
     */
    const SITE_PRE_INITIALIZE = 'site.pre_initialize';

    /**
     * The site.post_initialize event is thrown each time a site gets initialized.
     *
     * The event listener receives an
     * Segony\Site\Event\SiteInitializeEvent
     *
     * @var string
     *
     * @api
     */
    const SITE_POST_INITIALIZE = 'site.post_initialize';

    /**
     * The site.pre_dispatch event is thrown each time a site gets dispatched.
     *
     * The event listener receives an
     * Segony\Site\Event\SiteDispatchEvent
     *
     * @var string
     *
     * @api
     */
    const SITE_PRE_DISPATCH = 'site.pre_dispatch';

    /**
     * The site.post_dispatch event is thrown each time a site gets dispatched.
     *
     * The event listener receives an
     * Segony\Site\Event\SiteDispatchEvent
     *
     * @var string
     *
     * @api
     */
    const SITE_POST_DISPATCH = 'site.post_dispatch';

    /**
     * The site.pre_render event is thrown each time a site gets rendered.
     *
     * The event listener receives an
     * Segony\Site\Event\SiteRenderEvent
     *
     * @var string
     *
     * @api
     */
    const SITE_PRE_RENDER = 'site.pre_render';

    /**
     * The site.post_render event is thrown each time a site gets rendered.
     *
     * The event listener receives an
     * Segony\Site\Event\SiteRenderEvent
     *
     * @var string
     *
     * @api
     */
    const SITE_POST_RENDER = 'site.post_render';

        /**
     * The segment.pre_initialize event is thrown each time a segment gets initialized.
     *
     * The event listener receives an
     * Segony\Site\Event\SegmentInitializeEvent
     *
     * @var string
     *
     * @api
     */
    const SEGMENT_PRE_INITIALIZE = 'segment.pre_initialize';

    /**
     * The segment.post_initialize event is thrown each time a segment gets initialized.
     *
     * The event listener receives an
     * Segony\Site\Event\SegmentInitializeEvent
     *
     * @var string
     *
     * @api
     */
    const SEGMENT_POST_INITIALIZE = 'segment.post_initialize';

    /**
     * The segment.pre_dispatch event is thrown each time a segment gets dispatched.
     *
     * The event listener receives an
     * Segony\Site\Event\SegmentDispatchEvent
     *
     * @var string
     *
     * @api
     */
    const SEGMENT_PRE_DISPATCH = 'segment.pre_dispatch';

    /**
     * The segment.post_dispatch event is thrown each time a segment gets dispatched.
     *
     * The event listener receives an
     * Segony\Site\Event\SegmentDispatchEvent
     *
     * @var string
     *
     * @api
     */
    const SEGMENT_POST_DISPATCH = 'segment.post_dispatch';

    /**
     * The segment.pre_render event is thrown each time a segment gets rendered.
     *
     * The event listener receives an
     * Segony\Site\Event\SegmentRenderEvent
     *
     * @var string
     *
     * @api
     */
    const SEGMENT_PRE_RENDER = 'segment.pre_render';

    /**
     * The segment.post_render event is thrown each time a segment gets rendered.
     *
     * The event listener receives an
     * Segony\Site\Event\SegmentRenderEvent
     *
     * @var string
     *
     * @api
     */
    const SEGMENT_POST_RENDER = 'segment.post_render';

        /**
     * The layout.pre_initialize event is thrown each time a layout gets initialized.
     *
     * The event listener receives an
     * Segony\Site\LayoutInitializeEvent
     *
     * @var string
     *
     * @api
     */
    const LAYOUT_PRE_INITIALIZE = 'layout.pre_initialize';

    /**
     * The layout.post_initialize event is thrown each time a layout gets initialized.
     *
     * The event listener receives an
     * Segony\Site\LayoutInitializeEvent
     *
     * @var string
     *
     * @api
     */
    const LAYOUT_POST_INITIALIZE = 'layout.post_initialize';

    /**
     * The layout.pre_dispatch event is thrown each time a layout gets dispatched.
     *
     * The event listener receives an
     * Segony\Site\LayoutDispatchEvent
     *
     * @var string
     *
     * @api
     */
    const LAYOUT_PRE_DISPATCH = 'layout.pre_dispatch';

    /**
     * The layout.post_dispatch event is thrown each time a layout gets dispatched.
     *
     * The event listener receives an
     * Segony\Site\LayoutDispatchEvent
     *
     * @var string
     *
     * @api
     */
    const LAYOUT_POST_DISPATCH = 'layout.post_dispatch';

    /**
     * The layout.pre_render event is thrown each time a layout gets rendered.
     *
     * The event listener receives an
     * Segony\Site\LayoutRenderEvent
     *
     * @var string
     *
     * @api
     */
    const LAYOUT_PRE_RENDER = 'layout.pre_render';

    /**
     * The layout.post_render event is thrown each time a layout gets rendered.
     *
     * The event listener receives an
     * Segony\Site\LayoutRenderEvent
     *
     * @var string
     *
     * @api
     */
    const LAYOUT_POST_RENDER = 'layout.post_render';

}