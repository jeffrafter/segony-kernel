<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Storage;

use Segony\Test\TestCase;
use Segony\Storage\Storage;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class StorageTest extends TestCase
{

    private $storage;

    public function setUp()
    {
        $this->storage = new Storage();
    }

    public function tearDown()
    {
        $this->storage = null;
    }

    public function testSetUpValue()
    {
        $this->assertInstanceOf('Segony\Storage\Storage', $this->storage->set('name', 'segony'));
    }

    public function testHasKeyWhichNotExists()
    {
        $this->assertFalse($this->storage->has('name'));
    }

    public function testHasKeyWhichExists()
    {
        $this->storage->set('name', 'segony');
        $this->assertTrue($this->storage->has('name'));
    }

    public function testGetExistingValue()
    {
        $this->storage->set('name', 'segony');
        $this->assertSame('segony', $this->storage->get('name'));
    }

    public function testToGetTheDefaultValue()
    {
        $this->assertSame(1337, $this->storage->get('name', 1337));
    }

    public function testRemoveNonExistingValue()
    {
        $this->assertFalse($this->storage->remove('name'));
    }

    public function testRemoveExistingValue()
    {
        $this->storage->set('name', 'segony');
        $this->assertInstanceOf('Segony\Storage\Storage', $this->storage->remove('name'));
    }

    public function testAllOneDimension()
    {
        $this->storage->set('name', 'segony');
        $this->assertSame(['name' => 'segony'], $this->storage->all());
    }

    public function testAllRecursive()
    {
        $this->storage->set('name', 'segony')
                      ->set('info', ['version' => '1.0.0']);

        $this->assertSame(['name' => 'segony', 'info' => ['version' => '1.0.0']], $this->storage->all());
    }

    public function testValueIsTrue()
    {
        $this->storage->set('isAwesome', true);
        $this->assertTrue($this->storage->isTrue('isAwesome'));
    }

    public function testValueIsFalse()
    {
        $this->storage->set('isAwesome', false);
        $this->assertTrue($this->storage->isFalse('isAwesome'));
    }

    public function testValueIsEmpty()
    {
        $this->assertTrue($this->storage->isEmpty('name'));
    }

    public function testValueIsNotEmpty()
    {
        $this->assertFalse($this->storage->isNotEmpty('name'));
    }

    public function testValueIsNullNonExisting()
    {
        $this->assertTrue($this->storage->isNull('name'));
    }

    public function testValueIsNotNull()
    {
        $this->assertFalse($this->storage->isNotNull('name'));
    }

    public function testValueIsInstanceOf()
    {
        $this->storage->set('info', new \StdClass());
        $this->assertTrue($this->storage->isInstanceOf('info', 'StdClass'));
    }

    public function testValueIsNotInstanceOf()
    {
        $this->assertTrue($this->storage->isNotInstanceOf('name', 'StdClass'));
    }

    public function testFreezeRecursive()
    {
        $this->storage->set('name', 'Jon Doe');
        $this->storage->set('children', [['name' => 'Anna Doe']]);

        $this->storage->freeze();

        $this->assertTrue($this->storage->isFrozen());
        $this->assertTrue($this->storage->get('children')->isFrozen());
        $this->assertTrue($this->storage->get('children')->get(0)->isFrozen());
    }

    public function testFreezeOneDimension()
    {
        $this->storage->set('name', 'Jon Doe');
        $this->storage->set('children', [['name' => 'Anna Doe']]);

        $this->storage->freeze(false);

        $this->assertTrue($this->storage->isFrozen());
        $this->assertFalse($this->storage->get('children')->isFrozen());
        $this->assertFalse($this->storage->get('children')->get(0)->isFrozen());
    }

    public function testDeFrostWithInvalidSecret()
    {
        $this->setExpectedException('Segony\Exception', 'Invalid secret');
        $this->storage->freeze();
        $this->storage->defrost(1337);
    }

    public function testDeFrostWithValidSecret()
    {
        $secret = $this->storage->freeze();
        $this->storage->defrost($secret);
    }

    public function testToSetDataOnAFrozenStorage()
    {
        $this->setExpectedException('Segony\Exception', 'Cannot manipulate frozen storage');
        $this->storage->freeze();
        $this->storage->set('name', 'Jon Doe');
    }

    public function testToRemoveDataOnAFrozenStorage()
    {
        $this->setExpectedException('Segony\Exception', 'Cannot manipulate frozen storage');
        $this->storage->freeze();
        $this->storage->remove('name');
    }

    public function testToFreezeSubStorageWhichIsAlreadyFrozen()
    {
        $this->setExpectedException('Segony\Exception', 'Storage already frozen');
        $this->storage->set('child', ['name' => 'Anna Doe']);
        $this->storage->get('child')->freeze();
        $this->storage->freeze();
    }

    public function testToDefrostRecursive()
    {
        $this->storage->set('child', ['name' => 'Anna Doe']);
        $secret = $this->storage->freeze();
        $this->storage->defrost($secret);
    }

}