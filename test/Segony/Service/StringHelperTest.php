<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Service;

use Segony\Test\TestCase;
use Segony\Service\StringHelper;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class StringHelperTest extends TestCase
{

    private $stringHelper;

    public function setUp()
    {
        $this->stringHelper = new StringHelper();
    }

    public function tearDown()
    {
        $this->stringHelper = null;
    }

    public function testSlugifyWithEmptyValue()
    {
        $this->assertFalse($this->stringHelper->slugify(''));
    }

    public function testSlugifyWithString()
    {
        $this->assertSame('this-is-segony', $this->stringHelper->slugify('This is segony'));
    }

    public function testUnderscorifyWithEmptyValue()
    {
        $this->assertFalse($this->stringHelper->underscorify(''));
    }

    public function testUnderscorifyWithString()
    {
        $this->assertSame('this is segony', $this->stringHelper->underscorify('this is segony'));
    }

    public function testCamelCasifyWithEmptyValue()
    {
        $this->assertFalse($this->stringHelper->camelCasify(''));
    }

    public function testCamelCasifyWithString()
    {
        $this->assertSame('ThisIsSegony', $this->stringHelper->camelCasify('this_is_segony'));
    }

}