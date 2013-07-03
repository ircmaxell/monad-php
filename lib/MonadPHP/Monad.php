<?php

namespace MonadPHP;

class Monad {

    protected $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function unit($value) {
        if ($value instanceof $this) {
            return $value;
        }
        $class = get_class($this);
        return new $class($value);
    }

    public function bind($function, array $args = array()) {
        return $this->unit($this->runCallback($function, $this->value, $args));
    }

    public function extract() {
        if ($this->value instanceof self) {
            return $this->value->extract();
        }
        return $this->value;
    }

    protected function runCallback($function, $value, array $args = array()) {
        if ($value instanceof self) {
            return $value->bind($function, $args);
        }
        array_unshift($args, $value);
        return call_user_func_array($function, $args);
    }

}