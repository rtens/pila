<?php
namespace org\rtens\isolation\libraries;

use org\rtens\isolation\Assessment;
use org\rtens\isolation\classes\Bar;
use org\rtens\isolation\classes\Foo;
use org\rtens\isolation\Library;
use org\rtens\isolation\qualities\RecursiveFakes;
use org\rtens\isolation\qualities\Strictness;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class Prophecy implements Library, Assessment {

    public function name() {
        return "Prophecy";
    }

    public function url() {
        return 'http://github.com/phpspec/prophecy';
    }

    public function strictness(Strictness $quality) {
        /** @var Foo|ObjectProphecy $foo */
        $foo = (new Prophet())->prophesize(Foo::class);

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
        $fake = (new Prophet())->prophesize(Bar::class)->reveal();

        $quality->assert($fake);
    }
}