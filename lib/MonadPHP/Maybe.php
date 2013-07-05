<?php

namespace MonadPHP;

class Maybe extends Monad {

    public function bind($function){
        if (!is_null($this->value)) {
            return parent::bind($function);
        }
        return $this::unit(null);
    }

}
