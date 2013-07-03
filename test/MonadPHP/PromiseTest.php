<?php

namespace MonadPHP;

class PromiseTest extends \PHPUnit_Framework_TestCase {

    public function testBind() {
        $promise = new Promise;
        $calledSuccess = false;
        $calledFailure = false;
        $promise->when(function() use (&$calledSuccess) {
            $calledSuccess = true;
        }, function() use (&$calledFailure) {
            $calledFailure = true;
        });
        $this->assertFalse($calledSuccess);
        $this->assertFalse($calledFailure);

        $promise->succeed(true);
        $this->assertTrue($calledSuccess);
        $this->assertFalse($calledFailure);

        try {
            $promise->fail(true);
            $this->fail('No exception raised');
        } catch (\BadMethodCallException $e) {
            $this->assertFalse($calledFailure);
        }
    }

}