<?php
namespace org\rtens\isolation\libraries;

use org\rtens\isolation\classes\Bar;
use org\rtens\isolation\classes\Foo;
use org\rtens\isolation\Library;
use org\rtens\isolation\qualities\RecursiveFakes;
use org\rtens\isolation\qualities\Strictness;
use rtens\mockster\Mockster;

class Mockster3 implements Library {

    public function strictness(Strictness $quality) {
        /** @var Foo|Mockster $foo */
        $foo = new Mockster(Foo::class);
        Mockster::stub($foo->bas("meh"))->will()->return_("foo");
        $quality->assert($foo->mock());
    }

    public function recursiveFakes(RecursiveFakes $quality) {
        $quality->assert((new Mockster(Bar::class))->mock());
    }
}