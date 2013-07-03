<?php

namespace MonadPHP;

class MaybeTest extends \PHPUnit_Framework_TestCase {

    public function testBind() {
        $monad = new Maybe(1);
        $this->assertEquals("1", $monad->bind("strval"));
        
        $monad2 = $monad->unit(null);
        $called = false;
        $func = function($a) use (&$called) {
            $called = true;
        };
        $this->assertEquals(null, $monad2->bind($func));
        $this->assertFalse($called);
    }

}