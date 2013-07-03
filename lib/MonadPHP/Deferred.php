<?php

namespace MonadPHP;

class Deferred extends Promise {

    protected $isResolved = false;
    protected $succeed = null;

    protected $children = array();
    protected $success;
    protected $failure;

    public function __construct() {
    }

    public function succeed($value) {
        $this->resolve(true, $value);
    }

    public function fail($value) {
        $this->resolve(false, $value);
    }

}