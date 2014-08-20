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
class InvalidSpyNameTest extends SpyTestCase
{

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../Resource/environment');
    }

    public function testInvalidName()
    {
        $this->setExpectedException('Segony\Spy\SpyException');

        $this->getSpy('site', false);
    }

}