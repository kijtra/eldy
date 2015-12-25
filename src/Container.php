<?php
namespace Eldy;

class Container implements \ArrayAccess, \IteratorAggregate
{
    private $container = array();
    private $data = array();

    public function __construct($values = array(), &$data = array())
    {
        $this->container = $values;
        $this->data = $data;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } elseif(!empty($this->container[$offset])) {
            if (!empty($this->container[$offset][1])) {
                $this->container[$offset][] = $value;
            } else {
                $this->container[$offset] = new self(array(
                    $this->container[$offset],
                    $value
                ));
            }
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    public function getIterator() {
        return new \ArrayIterator($this->container);
    }

    public function __call($method, $args)
    {
        if ($args[0] instanceof \Closure) {
            $values = array(
                '@type' => $method
            );

            $container = new self($values, $this->data);
            $args[0] = $args[0]->bindTo($container);
            $args[0]();
            return $container;
        } elseif('data' === strtolower($method)) {
            if (!empty($args[0])) {
                if (count($args) > 1) {
                    return $this->data[$args[0]] = $args[1];
                } else if (array_key_exists($args[0], $this->data)) {
                    return $this->data[$args[0]];
                }
            } else {
                return $this->data;
            }
        }
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function __debugInfo() {
        return $this->container;
    }
}
