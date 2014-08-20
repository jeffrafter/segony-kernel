<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Debug\MemorizeBridge;

use Segony\Test\TestCase;
use Segony\Debug\Debug;
use Segony\Debug\MemorizeBridge\FileSystemMemorizer;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class FileSystemMemorizerTest extends TestCase
{

    static private $path;
    private $debug;
    private $memorizeBridge;

    static public function setUpBeforeClass()
    {
        self::$path = realpath(__DIR__ . '/../../../Resource') . '/temporary/FileSystemMemorizerTest';
    }

    public function setUp()
    {
        $this->debug = new Debug();
        $this->memorizeBridge = new FileSystemMemorizer();
    }

    public function tearDown()
    {
        $this->debug = null;
        $this->memorizeBridge = null;

        $this->cleanUpDebugPath();
    }

    private function cleanUpDebugPath()
    {
        if (false === file_exists(self::$path)) {
            return;
        }

        foreach (new \DirectoryIterator(self::$path) as $file) {
            if (true === $file->isDot() || true === $file->isLink()) {
                continue;
            }

            unlink($file->getPathname());
        }

        rmdir(self::$path);
    }

    public function testInvalidPathDefinition()
    {
        $this->setExpectedException('Segony\Exception');

        $this->debug->setMemorizeBridge($this->memorizeBridge);
        $this->debug->terminate();
    }

    public function testWithValidPathDefinition()
    {
        $this->memorizeBridge->setOptions([
            'path' => self::$path
        ]);
        $this->debug->setMemorizeBridge($this->memorizeBridge);
        $this->debug->terminate();

        $this->assertTrue(file_exists(self::$path . '/' . $this->debug->getId() . '.debug'));
    }

    public function testFsStorageWithThreshold()
    {
        $this->cleanUpDebugPath();

        $memorizeBridge = new FileSystemMemorizer(['path' => self::$path, 'threshold' => 10]);

        for ($i = 0; $i < 15; $i++) {
            $debug = new Debug();
            $debug->setMemorizeBridge($memorizeBridge);
            $debug->terminate();
        }

        $files    = 0;
        $iterator = new \DirectoryIterator(self::$path);

        foreach ($iterator as $file) {
            if (true === $file->isDot() || true === $file->isLink()) {
                continue;
            }

            $files++;
        }

        $this->assertSame(10, $files);
    }

    public function testToLoadSavedDebugItem()
    {
        $this->memorizeBridge->setOptions([
            'path' => self::$path
        ]);
        $this->debug->setMemorizeBridge($this->memorizeBridge);
        $this->debug->start('test');
        $this->debug->stop('test');
        $this->debug->terminate();

        $data = $this->memorizeBridge->load($this->debug->getId());
        $this->assertInternalType('array', $data);
    }

    public function testToLoadUnsavedDebugItem()
    {
        $this->setExpectedException('Segony\Exception');

        $this->memorizeBridge->setOptions([
            'path' => self::$path
        ]);
        $this->debug->setMemorizeBridge($this->memorizeBridge);
        $this->debug->terminate();

        $this->memorizeBridge->load('something');
    }

}