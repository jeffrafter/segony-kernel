<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Twig\Extension\Segment;

use Twig_Test_IntegrationTestCase as TestCase;
use Segony\Twig\Extension\SegmentExtension;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @covers \Segony\Twig\Extension\SegmentExtension
 */
class SegmentExtensionTest extends TestCase
{

    public function getExtensions()
    {
        $extension = new SegmentExtension();
        $extension->setSegmentResultSet(['my_id' => 'phpunit']);

        return [$extension];
    }

    public function getFixturesDir()
    {
        return realpath(__DIR__ . '/../../../../Resource/fixtures');
    }

}