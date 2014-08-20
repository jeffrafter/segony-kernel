<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Test;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @codeCoverageIgnore
 */
abstract class FileSystemTestCase extends TestCase
{

    protected $workspace;

    public function setUp()
    {
        $this->workspace = rtrim(sys_get_temp_dir(), '/') . '/' . time() . rand(0, 1000);
        mkdir($this->workspace, 0777, true);
        $this->workspace = realpath($this->workspace);
    }

    public function tearDown()
    {
        $this->clean($this->workspace);
    }

    protected function clean($file)
    {
        if (is_dir($file) && false === is_link($file)) {
            foreach (new \FilesystemIterator($file) as $childFile) {
                $this->clean($childFile);
            }

            return rmdir($file);
        }

        unlink($file);
    }

}