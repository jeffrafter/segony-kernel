<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Debug;

require_once __DIR__ . '/../../Resource/DebuggableItem.php';
require_once __DIR__ . '/../../Resource/InvalidDebuggableItem.php';

use Segony\Test\TestCase;
use Segony\Debug\Debug;
use Segony\Debug\MemorizeBridge\BlackHoleMemorizer;
use Segony\Debug\Sequence;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class DebugTest extends TestCase
{

    private $debug;

    public function setUp()
    {
        $this->debug = new Debug();
    }

    public function tearDown()
    {
        $this->debug = null;
    }

    public function testToStartSequence()
    {
        $sequence = $this->debug->start('test.debug');
        $this->assertInstanceOf('Segony\Debug\Sequence', $sequence);
    }

    public function testToStopSequence()
    {
        $sequence = $this->debug->start('test.debug');
        $this->assertInstanceOf('Segony\Debug\Debug', $this->debug->stop($sequence));
    }

    public function testToStopSequenceById()
    {
        $this->debug->start('test.debug');
        $this->assertInstanceOf('Segony\Debug\Debug', $this->debug->stop('test.debug'));
    }

    public function testToStopUndefinedSequence()
    {
        $this->setExpectedException('Segony\Exception');
        $this->debug->stop('test.debug');
    }

    public function testToAddCapturedDataToSequenceUsingStartAndStop()
    {
        $this->debug->start('test.debug', ['firstname' => 'Jon']);
        $this->debug->stop('test.debug', ['lastname' => 'Doe']);

        $data = $this->debug->all();

        $this->assertArrayHasKey('sequences', $data);
        $this->assertArrayHasKey('test.debug', $data['sequences']);
        $this->assertArrayHasKey('firstname', $data['sequences']['test.debug']);
        $this->assertArrayHasKey('lastname', $data['sequences']['test.debug']);
        $this->assertSame('Jon', $data['sequences']['test.debug']['firstname']);
        $this->assertSame('Doe', $data['sequences']['test.debug']['lastname']);
    }

    public function testToAddClassWhichImplementsTheDebuggableInterface()
    {
        $item = new \DebuggableItem();

        $this->debug->add($item);

        $data = $this->debug->all();

        $this->assertArrayHasKey('debuggableItems', $data);
        $this->assertArrayHasKey($item->getDebugId(), $data['debuggableItems']);
    }

    public function testToAddInvalidDebuggableItem()
    {
        $this->setExpectedException('Segony\Exception');

        $this->debug->add(new \InvalidDebuggableItem());
    }

    public function testToAddTheSameDebuggableItemTwice()
    {
        $this->setExpectedException('Segony\Exception');

        $this->debug->add(new \DebuggableItem());
        $this->debug->add(new \DebuggableItem());
    }

    public function testToAddTheMemorizerBridgeUsingTheConstructor()
    {
        $this->debug = new Debug(new BlackHoleMemorizer());
        $this->debug->terminate();
    }

}