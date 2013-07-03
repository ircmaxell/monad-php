<?php

namespace MonadPHP;

class MaybeTest extends \PHPUnit_Framework_TestCase {

    public function testBind() {
        $monad = new Maybe(1);
        $this->assertEquals(new Maybe("1"), $monad->bind("strval"));
        
        $monad2 = $monad->unit(null);
        $called = false;
        $func = function($a) use (&$called) {
            $called = true;
        };
        $this->assertEquals(new Maybe(null), $monad2->bind($func));
        $this->assertFalse($called);
    }

    public function testUnit() {
        $monad = new Maybe(1);
        $this->assertEquals($monad, $monad->unit($monad));
    }

    public function testExtract() {
        $monad = new Maybe(1);
        $this->assertEquals(1, $monad->extract());
    }

    public function testSelfUnit() {
        $monad = new Maybe(1);
        $m2 = $monad->unit($monad);
        $m3 = $monad->unit($m2);
        $this->assertEquals($monad, $m3);
    }

}