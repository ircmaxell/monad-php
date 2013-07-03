<?php

namespace MonadPHP;

class ListMonad extends Monad {

    public function unit($value) {
        if (!is_array($value) && !$value instanceof \Traversible) {
            throw new \InvalidArgumentException('Must be traversible');
        }
        return parent::unit($value);
    }

    public function bind($function){
        return $this->unit(array_map($function, $this->value));
    }

}