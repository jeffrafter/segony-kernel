<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Service;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class StringHelper
{

    /**
     * @param  string $value
     * @return string
     *
     * @api
     */
    public function slugify($value)
    {
        $value = preg_replace('~[^\\pL\d]+~u', '-', $value);
        $value = trim($value, '-');
        $value = iconv('utf-8', 'us-ascii//TRANSLIT', $value);
        $value = strtolower($value);
        $value = preg_replace('~[^-\w]+~', '', $value);

        if (empty($value)) {
            return false;
        }

        return $value;
    }

    /**
     * @param  string $value
     * @return string
     *
     * @api
     */
    public function underscorify($value)
    {
        if (empty($value)) {
            return false;
        }

        return strtolower(preg_replace(
            ['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'],
            ['\\1_\\2', '\\1_\\2'],
            strtr($value, '_', '.')
        ));
    }

    /**
     * @param  string $value
     * @return string
     *
     * @api
     */
    public function camelCasify($value)
    {
        if (empty($value)) {
            return false;
        }

        $data = [];

        foreach (explode('_', $value) as $part) {
            array_push($data, ucfirst($part));
        }

        return implode('', $data);
    }

}