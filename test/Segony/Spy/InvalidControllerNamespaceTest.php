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
class InvalidControllerNamespaceTest extends SpyTestCase
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
             ->copySkeletonFile('site/config', $this->getSpyRootDir() . '/test.yml')
             ->copySkeletonFile('site/backend_controller', $this->getSpyRootDir() . '/TestSiteController.php', ['Invalid', 'Invalid']);
    }

    public function tearDown()
    {
        $this->cleanPath($this->getSpyRootDir());
    }

    public function testInvalidNamespace()
    {
        $this->setExpectedException('Segony\Spy\SpyException');

        $spy = $this->getSpy('site', 'test');
        $spy->hunt();
    }

}