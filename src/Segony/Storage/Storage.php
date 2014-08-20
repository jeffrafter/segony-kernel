<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Storage;

use ArrayObject;
use Segony\Exception;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class Storage extends ArrayObject
{

    private $frozen = false;

    /**
     * Constructor
     *
     * @param array $data
     *
     * @api
     */
    public function __construct(array $data = null)
    {
        parent::__construct([], 0, 'ArrayIterator');
        $this->capture($data);
    }

    /**
     * @param  array $data
     * @return Storage
     *
     * @api
     */
    public function capture(array $data = null)
    {
        if (null === $data) {
            return;
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = new self($value);
            }

            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @throws \Segony\Exception If the storage is frozen
     *
     * @api
     */
    public function set($key, $value)
    {
        if ($this->isFrozen()) {
            throw new Exception('Cannot manipulate frozen storage');
        }

        if (is_array($value)) {
            $value = new self($value);
        }

        $this->offsetSet($key, $value);

        return $this;
    }

    /**
     * @param  string  $key
     * @return boolean
     *
     * @api
     */
    public function has($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     *
     * @api
     */
    public function get($key, $default = null)
    {
        if (false === $this->has($key)) {
            return $default;
        }

        return $this->offsetGet($key);
    }

    /**
     * @param  string $key
     * @return Storage
     * @throws \Segony\Exception If the container is frozen
     *
     * @api
     */
    public function remove($key)
    {
        if ($this->isFrozen()) {
            throw new Exception('Cannot manipulate frozen storage');
        }

        if (false === $this->has($key)) {
            return false;
        }

        $this->offsetUnset($key);

        return $this;
    }

    /**
     * @return array
     *
     * @api
     */
    public function all()
    {
        $data = [];

        foreach ($this->getArrayCopy() as $key => $value) {
            if ($value instanceof Storage) {
                $value = $value->all();
            }

            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * @param  string
     * @return boolean
     *
     * @api
     */
    public function isTrue($key)
    {
        return true === $this->get($key);
    }

    /**
     * @param  string
     * @return boolean
     *
     * @api
     */
    public function isFalse($key)
    {
        return false === $this->get($key);
    }

    /**
     * @param  string
     * @return boolean
     *
     * @api
     */
    public function isEmpty($key)
    {
        $value = $this->get($key);

        return true === empty($value);
    }

    /**
     * @param  string
     * @return boolean
     *
     * @api
     */
    public function isNotEmpty($key)
    {
        $value = $this->get($key);

        return false === empty($value);
    }

    public function isNull($key)
    {
        return null === $this->get($key);
    }

    /**
     * @param  string
     * @return boolean
     *
     * @api
     */
    public function isNotNull($key)
    {
        return null !== $this->get($key);
    }

    /**
     * @param  string $key
     * @param  mixed  $class
     * @return boolean
     *
     * @api
     */
    public function isInstanceOf($key, $class)
    {
        return true === ($this->get($key) instanceof $class);
    }

    /**
     * @param  string $key
     * @param  mixed  $class
     * @return boolean
     *
     * @api
     */
    public function isNotInstanceOf($key, $class)
    {
        return false === ($this->get($key) instanceof $class);
    }

    /**
     * Freeze the current storage
     *
     * @param  boolean $recursive
     * @param  string  $secret
     * @return string
     * @throws Segony\Exception If storage is already frozen
     *
     * @api
     */
    public function freeze($recursive = true, $secret = null)
    {
        if (false !== $this->frozen) {
            throw new Exception('Storage already frozen');
        }

        $this->frozen = $secret ?: uniqid();

        if (true === $recursive) {
            foreach ($this->getIterator() as $item) {
                if ($item instanceof Storage) {
                    $item->freeze(true, $this->frozen);
                }
            }
        }

        return $this->frozen;
    }

    /**
     * @param  string  $secret
     * @param  boolean $recursive
     * @return Storage
     * @throws Segony\Exception If the secret is invalid
     */
    public function defrost($secret, $recursive = true)
    {
        if ($this->frozen !== $secret) {
            throw new Exception('Invalid secret');
        }

        $this->frozen = false;

        if (true === $recursive) {
            foreach ($this->getIterator() as $item) {
                if ($item instanceof Storage) {
                    $item->defrost($secret, true);
                }
            }
        }

        return $this;
    }

    /**
     * @return boolean
     *
     * @api
     */
    public function isFrozen()
    {
        return false !== $this->frozen;
    }

}