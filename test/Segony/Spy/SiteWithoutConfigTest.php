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
 * @covers \Segony\Spy\SegmentSpy
 */
class SiteWithoutConfigTest extends SpyTestCase
{

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../Resource/environment');
    }

    protected function getSpyRootDir()
    {
        return $this->getRootDir() . '/app/site/test';
    }

    public function setUp()
    {
        $this->cleanPath($this->getSpyRootDir());

        $this->createDirectory($this->getSpyRootDir())
             ->copySkeletonFile('site/view', $this->getSpyRootDir() . '/test.html.twig')
             ->copySkeletonFile('site/client_controller', $this->getSpyRootDir() . '/test.js')
             ->copySkeletonFile('site/backend_controller', $this->getSpyRootDir() . '/TestSiteController.php', ['Test', 'Test']);
    }

    public function tearDown()
    {
        $this->cleanPath($this->getSpyRootDir());
    }

    public function testExpectedExceptionIfConfigNotExists()
    {
        $this->setExpectedException('Segony\Spy\SpyException');

        $spy = $this->getSpy('site', 'test');
        $spy->hunt();
    }

}