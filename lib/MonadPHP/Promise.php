<?php

namespace MonadPHP;

class Promise extends Monad {

    protected $isResolved = false;
    protected $succeed = null;

    protected $children = array();
    protected $success;
    protected $failure;

    public function __construct($success = null, $failure = null) {
        $this->success = $success;
        $this->failure = $failure;
    }

    public function unit($success = null, $failure = null) {
        return new static($success, $failure); 
    }

    public function bind($success, $failure) {
        $obj = $this->unit($success, $failure);
        if ($this->isResolved) {
            if ($this->succeed) {
                $obj->succeed($this->value);
            } else {
                $obj->fail($this->value);
            }
        } else {
            $this->children[] = $obj;
        }
        return $obj;
    }

    public function succeed($value) {
        $this->resolve(true, $value);
    }

    public function fail($value) {
        $this->resolve(false, $value);
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
            call_user_func($callback, $value);
        }
        $method = $status ? 'succeed' : 'fail';
        foreach ($this->children as $child) {
            $child->$method($value);
        }
    }

    public function when($success = null, $failure = null) {
        return $this->bind($success, $failure);
    }
}