<?php

namespace MonadPHP;

class IdentityTest extends \PHPUnit_Framework_TestCase {

    public function testBind() {
        $monad = new Identity(1);
        $this->assertEquals(1, $monad->bind('intval'));
        $this->assertEquals($monad->unit(1), $monad->lift('intval'));
    }

    public function testBindUnit() {
        $monad = new Identity(1);
        $this->assertEquals($monad, $monad->bind($monad->unit));
    }

}