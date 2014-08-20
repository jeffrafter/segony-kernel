<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Spy;

use Segony\Test\SpyTestCase;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Spy\Spy
 * @covers \Segony\Spy\SiteSpy
 * @covers \Segony\Spy\LayoutSpy
 */
class NestedSiteSpyTest extends SpyTestCase
{

    private $spyInstance;

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../Resource/environment');
    }

    protected function getSpyRootDir()
    {
        return $this->getRootDir() . '/app/site/nested/test';
    }

    public function setUp()
    {
        $this->cleanPath($this->getSpyRootDir());

        $this->createDirectory($this->getSpyRootDir(), true)
             ->copySkeletonFile('site/config', $this->getSpyRootDir() . '/test.yml')
             ->copySkeletonFile('site/view', $this->getSpyRootDir() . '/test.html.twig')
             ->copySkeletonFile('site/client_controller', $this->getSpyRootDir() . '/test.js')
             ->copySkeletonFile('site/backend_controller', $this->getSpyRootDir() . '/TestSiteController.php', ['Nested\Test', 'Test']);

        $this->spyInstance = $this->getSpy('site', 'nested/test');
        $this->spyInstance->hunt();
    }

    public function tearDown()
    {
        $this->cleanPath($this->getSpyRootDir());
    }

    public function testView()
    {
        $this->assertSame('test.html.twig', $this->spyInstance->getView());
    }

    public function testConfig()
    {
        $this->assertInstanceOf('Segony\Storage\Storage', $this->spyInstance->getConfig());
        $this->assertTrue($this->spyInstance->getConfig()->has('title'));
        $this->assertSame('Skeleton Configuration', $this->spyInstance->getConfig()->get('title'));
    }

    public function testBackendController()
    {
        $this->assertInstanceOf('Segony\Site\SiteController', $this->spyInstance->getBackendController());
    }

    public function testClientController()
    {
        $this->assertSame('site/nested/test', $this->spyInstance->getClientController());
    }

}