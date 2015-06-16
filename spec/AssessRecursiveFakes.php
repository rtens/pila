<?php
namespace spec\org\rtens\isolation;

use org\rtens\isolation\classes\Bar;
use org\rtens\isolation\classes\Foo;
use org\rtens\isolation\qualities\RecursiveFakes;
use rtens\scrut\tests\statics\StaticTestSuite;

class AssessRecursiveFakes extends StaticTestSuite {

    function passes() {
        $quality = new RecursiveFakes(__CLASS__);
        $quality->assert(new AssessRecursiveFakes_Bar());
        $this->assert($quality->getResult()->getPoints(), 1);
        $this->assert($quality->getResult()->getMessage(), 'yes');
        $this->assert($quality->getResult()->getPreferred(), 'yes');
    }

    function fails() {
        $quality = new RecursiveFakes(__CLASS__);
        $quality->assert(new Bar());
        $this->assert($quality->getResult()->getPoints(), -1);
        $this->assert($quality->getResult()->getMessage(), 'no');
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