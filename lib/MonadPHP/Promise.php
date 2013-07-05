<?php

namespace MonadPHP;

class Promise extends Monad {

    const unit = "MonadPHP\Promise::unit";

    protected $isResolved = false;
    protected $succeed = null;

    protected $children = array();
    protected $success;
    protected $failure;

    public function __construct($success = null, $failure = null) {
        $this->success = $success;
        $this->failure = $failure;
    }

    public static function unit($success = null, $failure = null) {
        return new Promise($success, $failure); 
    }

    public function bind($success, $failure) {
        $obj = $this->unit($success, $failure);
        if ($this->isResolved) {
            $obj->resolve($this->succeed, $this->value);
        } else {
            $this->children[] = $obj;
        }
        return $obj;
    }

    protected function resolve($status, $value) {
        if ($this->isResolved) {
            throw new \BadMethodCallException('Promise already resolved');
        }
        $this->value = $value;
        $this->isResolved = true;
        $this->succeed = $status;
        $callback = $status ? $this->success : $this->failure;
        if ($callback) {
            $this->value = call_user_func($callback, $value);
        }
        foreach ($this->children as $child) {
            $child->resolve($status, $this->value);
        }
    }

    public function when($success = null, $failure = null) {
        return $this->bind($success, $failure);
    }
}
