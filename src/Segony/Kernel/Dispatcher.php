<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Kernel;

use Symfony\Component\DependencyInjection\Scope;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Segony\Routing\RouteMatcher;
use Segony\Spy\SiteSpy;
use Segony\Spy\LayoutSpy;
use Segony\Segment\SegmentWorker;
use Segony\Controller\Controller;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class Dispatcher
{

    private $container;
    private $request;

    /**
     * @param ContainerInterface $container
     * @param Request            $request
     */
    public function __construct(ContainerInterface $container, Request $request)
    {
        $this->container = $container;
        $this->request   = $request;
    }

    /**
     * @param  string $path
     * @return SiteSpy
     */
    private function getSiteSpy($path)
    {
        $ss = new SiteSpy($path, $this->container);
        $ss->hunt();

        return $ss;
    }

    /**
     * @param  string $id
     * @return LayoutSpy
     */
    private function getLayoutSpy($id)
    {
        $ls = new LayoutSpy($id, $this->container);
        $ls->hunt();

        return $ls;
    }

    /**
     * Dispatchs the application
     *
     * @return Response
     */
    public function dispatch()
    {
        $matcher = new RouteMatcher($this->container, $this->request);
        $match   = $matcher->match();

        if (null === $match) {
            throw new \Exception('Cannot find site');
        }

        $path = $match->getSite() ? $match->getSite() : $match->getRoute();

        $ss = $this->getSiteSpy($path);
        $ls = $this->getLayoutSpy($ss->getConfig()->get('layout', 'base'));

        $sw = new SegmentWorker();
        $sw->setContainer($this->container);

        foreach ($ls->getConfig()->get('segment', []) as $id => $definition) {
            $sw->register($id, $definition);
        }

        foreach ($ss->getConfig()->get('segment', []) as $id => $definition) {
            $sw->register($id, $definition);
        }

        $site = $ss->getBackendController();
        $site->handle(Controller::MODE_INITIALIZE);

        $layout = $ls->getBackendController();
        $layout->handle(Controller::MODE_INITIALIZE);

        $site->handle(Controller::MODE_DISPATCH);
        $layout->handle(Controller::MODE_DISPATCH);

        $segmentResultSet = $sw->process();

        $this->container
            ->get('twig_segment_extension')
            ->setSegmentResultSet($segmentResultSet)
        ;

        $content = $site->handle(Controller::MODE_RENDER, [
            ['site_config' => $site->getConfig()->all()]
        ]);

        $content .= PHP_EOL;
        $content .= $this->container->get('twig')
                         ->render(
                             '@main/client_dispatcher.html.twig',
                             [
                                 'segmentInformation' => $sw->getInformation(),
                                 'environment'        => $this->container->getParameter('kernel.environment'),
                                 'route'              => $path,
                                 'debugId'            => $this->container->get('debug')->getId(),
                                 'config'             => $this->container->get('config')->all()
                             ]
                         );

        $output = $layout->handle(Controller::MODE_RENDER, [
            [
                'content' => $content
            ]
        ]);

        $response = new Response();
        $response->setContent($output);

        // var_dump($segmentResultSet);
        // var_dump($this->container->get('twig_segment_extension')->getDebugInfo()['requiredSegments']);
        #trigger_error("Kann nicht durch 0 teilen", E_WARNING);

        $this->container->get('debug')->add($site)
                                      ->add($layout);

        return $response;
    }

}