<?php

namespace MonadPHP;

class ListMonadTest extends \PHPUnit_Framework_TestCase {

    public function testUnitFailure() {
        $this->setExpectedException('InvalidArgumentException');
        $monad = new ListMonad(123);
    }

    public function testBindEmpty() {
        $monad = new ListMonad(array());
        $called = 0;
        $monad->bind(function($value) use (&$called) { $called++; return $value; });
        $this->assertEquals(0, $called);
    }

    public function testBindNotEmpty() {
        $monad = new ListMonad(array(1, 2, 3));
        $called = 0;
        $new = $monad->bind(function($value) use (&$called) { $called++; return $value; });
        $this->assertEquals(3, $called);
        $this->assertEquals($monad, $new);
        $this->assertEquals(array(1, 2, 3), $new->extract());
    }

    public function testComposedListMonad() {
        $monad = new ListMonad(array(1, 2, 3, null, 4));
        $newMonad = $monad->bind(function ($v) { return new Maybe($v); });
        $doubled = $newMonad->bind(function ($v) { return $v * 2; });
        $this->assertEquals(array(2, 4, 6, null, 8), $doubled->extract());
    }


}