<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Spy;

use Segony\Test\SegmentSpyTestCase;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Spy\Spy
 * @covers \Segony\Spy\SegmentSpy
 */
class SegmentSpyTest extends SegmentSpyTestCase
{

    private $spyInstance;

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../Resource/environment');
    }

    protected function getSpyRootDir()
    {
        return $this->getRootDir() . '/app/segment/test';
    }

    public function setUp()
    {
        $this->cleanPath($this->getSpyRootDir());

        $this->createDirectory($this->getSpyRootDir(), true)
             ->createDirectory($this->getSpyRootDir() . '/view', true)
             ->copySkeletonFile('segment/config', $this->getSpyRootDir() . '/test.yml')
             ->copySkeletonFile('segment/view', $this->getSpyRootDir() . '/view/test.html.twig')
             ->copySkeletonFile('segment/client_controller', $this->getSpyRootDir() . '/test.js')
             ->copySkeletonFile('segment/backend_controller', $this->getSpyRootDir() . '/TestSegmentController.php', ['Test', 'Test']);

        $this->spyInstance = $this->getSegmentSpy('segment', 'test', 'my_key');
        $this->spyInstance->hunt();
    }

    public function tearDown()
    {
        $this->cleanPath($this->getSpyRootDir());
    }

    public function testInvalidEmbeddingKey()
    {
        $this->setExpectedException('Segony\Spy\SpyException');
        $this->getSegmentSpy('segment', 'invalid', false);
    }

}