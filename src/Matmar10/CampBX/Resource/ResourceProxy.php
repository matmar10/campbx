<?php

namespace Matmar10\Campbx\Resource;

use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\CurrencyPair;
use Matmar10\Money\Entity\Money;
use RuntimeException;

class ResourceProxy
{

    protected $data;

    protected $validKeys;

    public function __construct(array $data, array $validKeys)
    {
        $this->data = $data;
        $this->validKeys = $validKeys;
    }

    public function __call($name, $arguments)
    {

        if(0 === strpos($name, 'set') && strlen($name) > 3) {
            $key = lcfirst(substr($name, 3));
            return call_user_func(array($this, '__set'), $key, $arguments[0]);
        }

        if(0 === strpos($name, 'get') && strlen($name) > 3) {
            $key = lcfirst(substr($name, 3));
            return call_user_func(array($this, '__get'), $key);
        }

        throw new RuntimeException(sprintf('Undefined method call: %s', $name));
    }

    public function __set($name, $value)
    {
        $this->assertValidKey($name);
        $this->data[$name] = $value;
    }

    public function get($name)
    {
        return $this->__get($name);
    }

    public function __get($name)
    {
        $this->assertValidKey($name);
        return $this->data[$name];
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    protected function assertValidKey($name)
    {
        if(false === array_search($name, $this->validKeys)) {
            throw new RuntimeException(sprintf("Invalid member variable '%s' requested (expected: %s)" , $name, implode(', ', $this->validKeys)));
        }
    }

    public function getValidKeys()
    {
        return $this->validKeys;
    }

    public function getData()
    {
        return $this->data;
    }
}
