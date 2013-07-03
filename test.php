<?php

require_once 'vendor/autoload.php';

$list = new MonadPHP\ListMonad(array(1, 2, 3, null, 4));

$list->lift('strval', 'strval');

$list->double = function($val) use ($list) {
    return $val * 2;
};

$list->maybe = function($val) {
    return new MonadPHP\Maybe($val);
};
var_dump($list->maybe());
var_dump($list->maybe()->double());

