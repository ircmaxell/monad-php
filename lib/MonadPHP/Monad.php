<?php

namespace MonadPHP;

abstract class Monad {

    protected $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function __get($name) {
        $name = strtolower($name);
        if (method_exists($this, $name)) {
            $cb = array($this, $name);
            return function() use ($cb) {
                return call_user_func_array($cb, func_get_args());
            };
        }
        return null;
    }

    public function unit($value) {
        $class = get_class($this);
        return new $class($value);
    }

    public function bind($function, array $args = array()) {
        array_unshift($args, $this->value);
        return call_user_func_array($function, $args);
    }

}