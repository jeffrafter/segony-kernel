<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Test;

use PHPUnit_Framework_TestCase as AbstractTestCase;
use Segony\Exception;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @codeCoverageIgnore
 */
class TestCase extends AbstractTestCase
{

    /**
     * @param  string  $path
     * @param  boolean $nested
     * @return TestCase
     */
    protected function createDirectory($path, $nested = false)
    {
        if (is_dir($path)) {
            return $this;
        }

        if (true === $nested) {
            mkdir($path, 0777, true);
            return $this;
        }

        mkdir($path);

        return $this;
    }

    /**
     * @param  string $skeleton
     * @param  string $destination
     * @param  array $data
     * @return TestCase
     */
    protected function copySkeletonFile($skeleton, $destination, array $data = [])
    {
        $skeleton = realpath(dirname(__FILE__) . '/../../../test/Resource/skeleton') . '/' . $skeleton . '.skeleton';

        if (false === file_exists($skeleton)) {
            throw new Exception(sprintf('Cannot find skeleton "%s"', $skeleton));
        }

        $content = file_get_contents($skeleton);
        file_put_contents($destination, vsprintf($content, $data));

        return $this;
    }

    /**
     * @param  string $path
     * @return TestCase
     */
    protected function cleanPath($path)
    {
        if (false === is_dir($path)) {
            return $this;
        }

        $iterator = new \DirectoryIterator($path);

        foreach ($iterator as $item) {
            if (true === $item->isLink() || true === $item->isDot()) {
                continue;
            }

            if ($item->isDir()) {
                $this->cleanPath($item->getPathname());
                continue;
            }

            unlink($item->getPathname());
        }

        rmdir($path);

        return $this;
    }

}