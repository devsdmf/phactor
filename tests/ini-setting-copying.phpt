--TEST--
Testing the correct copying of ini settings
--FILE--
<?php

ini_set('error_reporting', 32767);
var_dump(ini_get('error_reporting'));
ini_set('error_reporting', 1);
var_dump(ini_get('error_reporting'));

$actorSystem = new ActorSystem(true);

spawn('test', Test::class);

class Test extends Actor
{
    public function __construct()
    {
        $this->send($this, 1);
    }

    public function receive($sender, $message)
    {
        var_dump(ini_get('error_reporting'));
        ActorSystem::shutdown();
    }
}

$actorSystem->block();
--EXPECT--
string(5) "32767"
string(1) "1"
string(1) "1"
