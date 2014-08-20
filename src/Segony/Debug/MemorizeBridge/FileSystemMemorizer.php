<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Debug\MemorizeBridge;

use Segony\Exception;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class FileSystemMemorizer extends AbstractMemorizeBridge
{

    private function cleanUp()
    {
        if (false === $this->options->has('threshold') && false === $this->options->get('threshold', false)) {
            return;
        }

        $files = [];
        foreach (new \DirectoryIterator($this->options->get('path')) as $item) {
            if (true === $item->isDot() || true === $item->isLink()) {
                continue;
            }

            array_push($files, [
                'file' => $item->getPathname(),
                'date' => $item->getCTime()
            ]);
        }

        uksort($files, function ($a, $b) {
            return $a['date'] > $b['date'];
        });

        $deletions = array_slice($files, 10);

        foreach ($deletions as $item) {
            unlink($item['file']);
        }
    }

    private function getDebugFileName($id)
    {
        if (false === $this->options->has('path')) {
            throw new Exception('Invalid config option "path"');
        }

        return $this->options->get('path') . '/' . $id . '.debug';
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function save($id, array $data = null)
    {
        $file = $this->getDebugFileName($id);

        if (false === file_exists($this->options->get('path'))) {
            mkdir($this->options->get('path'), 0777, true);
        }

        file_put_contents($file, serialize($data));
        $this->cleanUp();

        return $id;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function load($id)
    {
        $file = $this->getDebugFileName($id);

        if (false === file_exists($file)) {
            throw new Exception(sprintf('Cannot find debug entity by identifier "%s"', $id));
        }

        return unserialize(file_get_contents($file));
    }

}