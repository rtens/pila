<?php
namespace org\rtens\isolation\libraries;

use org\rtens\isolation\classes\Bar;
use org\rtens\isolation\classes\Foo;
use org\rtens\isolation\Library;
use org\rtens\isolation\qualities\RecursiveFakes;
use org\rtens\isolation\qualities\Strictness;
use Prophecy\Prophecy\ObjectProphecy;

class Prophet implements Library {

    public function strictness(Strictness $quality) {
        /** @var Foo|ObjectProphecy $foo */
        $foo = (new \Prophecy\Prophet())
            ->prophesize(Foo::class);

        /** @var Foo $fake */
        $fake = $foo->reveal();

        // Non-strict here
        $quality->assert($fake);

        /** @var \Prophecy\Prophecy\MethodProphecy $bas */
        $bas = $foo->bas("meh");
        $bas->willReturn("foo");

        // But strict as soon as anything is configured
        $quality->assert($fake, 'strict if anything is stubbed');
    }

    public function recursiveFakes(RecursiveFakes $quality) {
        $fake = (new \Prophecy\Prophet())
            ->prophesize(Bar::class)
            ->reveal();

        $quality->assert($fake);
    }
}