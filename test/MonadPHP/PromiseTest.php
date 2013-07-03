<?php

namespace MonadPHP;

class PromiseTest extends \PHPUnit_Framework_TestCase {

    public function testFail() {
        $promise = new Deferred;
        $calledSuccess = false;
        $calledFailure = false;
        $promise->when(function() use (&$calledSuccess) {
            $calledSuccess = true;
        }, function() use (&$calledFailure) {
            $calledFailure = true;
        });
        $this->assertFalse($calledSuccess);
        $this->assertFalse($calledFailure);

        $promise->fail(true);
        $this->assertFalse($calledSuccess, 'Not successful');
        $this->assertTrue($calledFailure, 'Failed!');

        try {
            $promise->succeed(true);
            $this->fail('No exception raised');
        } catch (\BadMethodCallException $e) {
            $this->assertFalse($calledSuccess);
        }
    }


    public function testSucceed() {
        $promise = new Deferred;
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
        $this->assertTrue($calledSuccess, 'Success was not called');
        $this->assertFalse($calledFailure, 'Fail was called');

        try {
            $promise->fail(true);
            $this->fail('No exception raised');
        } catch (\BadMethodCallException $e) {
            $this->assertFalse($calledFailure, 'Fail is true!');
        }
    }

    public function testAlreadyResolvedPromise() {
        $promise = new Deferred();
        $promise->succeed(true);
        $calledSuccess = false;
        $calledFailure = false;
        $promise->when(function() use (&$calledSuccess) {
            $calledSuccess = true;
        }, function() use (&$calledFailure) {
            $calledFailure = true;
        });
        $this->assertTrue($calledSuccess);
        $this->assertFalse($calledFailure);
       
    }

    public function testPromiseValues() {
        $promise = new Deferred();
        $receivedValue = null;
        $promise->when(function($a) {
            return $a + 1;
        })->when(function($b) use (&$receivedValue) {
            $receivedValue = $b;
        });
        $this->assertEquals(null, $receivedValue);
        $promise->succeed(2);
        $this->assertEquals(3, $receivedValue);
    }

}