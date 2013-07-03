<?php

namespace MonadPHP;

class Deferred extends Promise {

    public function succeed($value) {
        $this->resolve(true, $value);
    }

    public function fail($value) {
        $this->resolve(false, $value);
    }

}