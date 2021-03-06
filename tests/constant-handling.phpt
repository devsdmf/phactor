--TEST--
Constant handling
--DESCRIPTION--
Ensure that constants are correctly handled.
--FILE--
<?php

use phactor\{ActorSystem, Actor, ActorRef};

const A = [1,2,3];

$actorSystem = new ActorSystem();

const B = [4,5,6];

class Test extends Actor
{
    const C = [7,8,9];

    public function __construct()
    {
        var_dump(self::C);
        new ActorRef(Test2::class, [ActorRef::fromActor($this)], 'test2');
    }

    public function receive()
    {
        [$sender, $message] = $this->receiveBlock();
        var_dump(A);
        var_dump(defined('B') ? B : "Constant 'B' not found");
        var_dump(self::C, $message);
        $this->send($sender, self::C);
    }
}

class Test2 extends Actor
{
    const D = [10,11,12];

    public function __construct(ActorRef $ar)
    {
        var_dump(self::D);
        $this->send($ar, [ActorRef::fromActor($this)->getName(), self::D]);
    }

    public function receive()
    {
        $message = $this->receiveBlock();
        var_dump(A);
        var_dump(defined('B') ? B : "Constant 'B' not found");
        var_dump(self::D, $message);
        ActorSystem::shutdown();
    }
}

new ActorRef(Test::class, [], 'test');
--EXPECT--
array(3) {
  [0]=>
  int(7)
  [1]=>
  int(8)
  [2]=>
  int(9)
}
array(3) {
  [0]=>
  int(10)
  [1]=>
  int(11)
  [2]=>
  int(12)
}
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
}
string(22) "Constant 'B' not found"
array(3) {
  [0]=>
  int(7)
  [1]=>
  int(8)
  [2]=>
  int(9)
}
array(3) {
  [0]=>
  int(10)
  [1]=>
  int(11)
  [2]=>
  int(12)
}
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
}
string(22) "Constant 'B' not found"
array(3) {
  [0]=>
  int(10)
  [1]=>
  int(11)
  [2]=>
  int(12)
}
array(3) {
  [0]=>
  int(7)
  [1]=>
  int(8)
  [2]=>
  int(9)
}
