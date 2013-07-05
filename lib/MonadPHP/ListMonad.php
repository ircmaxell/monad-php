<?php

namespace MonadPHP;

class ListMonad extends Monad {

    const unit = "MonadPHP\ListMonad::unit";

    public function __construct($value) {
        if (!is_array($value) && !$value instanceof \Traversable) {
            throw new \InvalidArgumentException('Must be traversable');
        }
        return parent::__construct($value);
    }

    public function bind($function, array $args = array()) {
        $result = array();
        foreach ($this->value as $value) {
            $result[] = $this->runCallback($function, $value, $args);
        }
        return $this::unit($result);
    }

    public function extract() {
        $ret = array();
        foreach ($this->value as $value) {
            if ($value instanceof Monad) {
                $ret[] = $value->extract();
            } else {
                $ret[] = $value;
            }
        }
        return $ret;
    }

}
