<?php
namespace spec\rtens\isolation;

use rtens\isolation\classes\Bar;
use rtens\isolation\classes\Foo;
use rtens\isolation\Library;
use rtens\isolation\qualities\RecursiveFakes;
use rtens\scrut\tests\statics\StaticTestSuite;

class AssessRecursiveFakes extends StaticTestSuite {

    function passes() {
        $quality = new RecursiveFakes(new AssessRecursiveFakes_Library());
        $quality->assert(new AssessRecursiveFakes_Bar());
        $this->assert($quality->getResult()->getPoints(), 1);
        $this->assert($quality->getResult()->getMessage(), 'yes');
        $this->assert($quality->getResult()->getPreferred(), 'yes');
    }

    function fails() {
        $quality = new RecursiveFakes(new AssessRecursiveFakes_Library());
        $quality->assert(new Bar());
        $this->assert($quality->getResult()->getPoints(), 0);
        $this->assert($quality->getResult()->getMessage(), 'no');
    }
}

class AssessRecursiveFakes_Library implements Library {

    public function name() {
    }

    public function url() {
    }
}

class AssessRecursiveFakes_Bar extends Bar {

    function returnFoo() {
        return new Foo();
    }

    function returnBar() {
        return new AssessRecursiveFakes_Bar();
    }
}