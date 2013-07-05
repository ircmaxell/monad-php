<?php

namespace MonadPHP;

class Chain extends Maybe {

    public function __set($name, $value) {
        return $this->bind(function($obj) use($name, $value) {
            $obj->$name = $value;
            return $value;
        });
    }

    public function __get($name) {
        return $this->bind(function($obj) use($name) {
            if (isset($obj->$name)) {
                return $obj->$name;
            }
            return null;
        });
    }

    public function __call($name, array $args = array()) {
        return $this->bind(function($obj) use ($name, $args) {
            if (is_callable(array($obj, $name))) {
                return call_user_func_array(array($obj, $name), $args);
            }
            return null;
        });
    }

}