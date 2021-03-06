--TEST--
Store values
--DESCRIPTION--
Ensure that different values are correctly handled by the store.
--FILE--
<?php

use phactor\{ActorSystem, Actor, ActorRef};

$actorSystem = new ActorSystem();

class Test extends Actor
{
    public function receive()
    {
        var_dump($this->receiveBlock());
        ActorSystem::shutdown();
    }
}

class Test2 extends Actor
{
    private $a = null;
    private $b = 1;
    private $c = 2.0;
    private $d = 'three';
    private $e = [null, 4, 5.0, 'six', []];

    public function __construct()
    {
        $this->send('test', [$this->a, $this->b, $this->c, $this->d, $this->e]);
    }

    public function receive() {}
}

new ActorRef(Test::class, [], 'test');
new ActorRef(Test2::class, [], 'test2');
--EXPECT--
array(5) {
  [0]=>
  NULL
  [1]=>
  int(1)
  [2]=>
  float(2)
  [3]=>
  string(5) "three"
  [4]=>
  array(5) {
    [0]=>
    NULL
    [1]=>
    int(4)
    [2]=>
    float(5)
    [3]=>
    string(3) "six"
    [4]=>
    array(0) {
    }
  }
}
