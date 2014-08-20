<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony;

use Segony\Test\FileSystemTestCase;
use Segony\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Kernel
 * @covers \Segony\Kernel\Dispatcher
 * @covers \Segony\Controller\Controller::runInitialize
 * @covers \Segony\Controller\Controller::runDispatch
 * @covers \Segony\Controller\Controller::runRender
 * @covers \Segony\Controller\Controller::getEvent
 *
 * Cased by the output test...
 */
class KernelTest extends FileSystemTestCase
{

    private $resourceDir;

    public function setUp()
    {
        parent::setUp();

        $this->resourceDir = realpath(__DIR__ . '/../Resource');

        $this
            ->initBaseConfig()
            ->initLayout()
            ->initSite()
            ->initSegment()
        ;
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    private function skeleton($name, $destination, array $args = [])
    {
        $template = file_get_contents($this->resourceDir . '/skeleton/' . $name . '.skeleton');
        $content  = vsprintf($template, $args);

        file_put_contents($this->workspace . '/' . $destination, $content);

        return $this;
    }

    private function initBaseConfig()
    {
        mkdir($this->workspace . '/app/config', 0777, true);

        file_put_contents(
            $this->workspace . '/app/config/config.yml',
            file_get_contents($this->resourceDir . '/environment/app/config/config.yml')
        );

        file_put_contents(
            $this->workspace . '/app/config/config_dev.yml',
            file_get_contents($this->resourceDir . '/environment/app/config/config_dev.yml')
        );

        file_put_contents(
            $this->workspace . '/app/config/service.yml',
            file_get_contents($this->resourceDir . '/environment/app/config/service.yml')
        );

        return $this;
    }

    private function initLayout()
    {
        mkdir($this->workspace . '/app/layout/my_base', 0777, true);

        $this
            ->skeleton(
                'layout/backend_controller',
                'app/layout/my_base/MyBaseLayoutController.php',
                ['MyBase', 'MyBase']
            )
        ;

        $myBaseView = <<<EOT
<!DOCTYPE html>
<html>
    <head>
        <title>Just another test</title>
    </head>
    <body>
        {{ segment('stuff') }}
        {{ content }}
    </body>
</html>
EOT;

        $myBaseConfig = <<<EOT
segment:
    stuff:
        segment: 'say_hello'
        config:
            name: 'PHPUnit'
            greet: 'Hi'
EOT;

        file_put_contents($this->workspace . '/app/layout/my_base/my_base.yml', $myBaseConfig);
        file_put_contents($this->workspace . '/app/layout/my_base/my_base.html.twig', $myBaseView);

        return $this;
    }

    private function initSite()
    {
        mkdir($this->workspace . '/app/site/startpage', 0777, true);

        $this
            ->skeleton(
                'site/backend_controller',
                'app/site/startpage/StartpageSiteController.php',
                ['Startpage', 'Startpage']
            )
            ->skeleton(
                'site/view',
                'app/site/startpage/startpage.html.twig'
            )
        ;

        $startpageConfig = <<<EOT
layout: 'my_base'
segment:
    some:
        segment: 'say_hello'
        config:
            name: 'Jon Doe'
EOT;

        file_put_contents($this->workspace . '/app/site/startpage/startpage.yml', $startpageConfig);

        return $this;
    }

    private function initSegment()
    {
        mkdir($this->workspace . '/app/segment/say_hello', 0777, true);
        mkdir($this->workspace . '/app/segment/say_hello/view', 0777, true);

        $this
            ->skeleton(
                'segment/backend_controller',
                'app/segment/say_hello/SayHelloSegmentController.php',
                ['SayHello', 'SayHello']
            )
        ;

        $sayHelloConfig = <<<EOT
greet: 'Hello'
EOT;

        file_put_contents($this->workspace . '/app/segment/say_hello/view/say_hello.html.twig', '{{ segment.some }}');
        file_put_contents($this->workspace . '/app/segment/say_hello/say_hello.yml', $sayHelloConfig);

        return $this;
    }

    private function request($item)
    {
        if (true === is_string($item)) {
            $_SERVER['REQUEST_URI'] = $item;
            $item = Request::createFromGlobals();
        }


        $kernel = new Kernel('dev', true, $this->workspace);

        return [$kernel->handle($item), $kernel];
    }

    public function testFailureResponse()
    {
        $this->setExpectedException('Exception', 'Cannot find site');
        list($response, $kernel) = $this->request('/does-not-exist');
    }

    public function testSuccessfulResponse()
    {
        list($response, $kernel) = $this->request('/startpage');

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @runInSeparateProcess
     * @outputBuffering enabled
     */
    public function testToTerminateSuccessfulResponse()
    {
        $_SERVER['REQUEST_URI'] = '/startpage';
        $request = Request::createFromGlobals();

        list($response, $kernel) = $this->request($request);

        ob_start();
        $kernel->terminate($request, $response);
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertContains('<title>Just another test</title>', $content);
        $this->assertContains('<div class="segment" id="stuff" data-segment="say_hello"></div>', $content);
    }

}