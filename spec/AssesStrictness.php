<?php
namespace spec\org\rtens\isolation;

use org\rtens\isolation\classes\Foo;
use org\rtens\isolation\qualities\Strictness;
use rtens\scrut\tests\statics\StaticTestSuite;

class AssesStrictness extends StaticTestSuite {

    function passes() {
        $quality = new Strictness(__CLASS__);
        $quality->assert(new Foo());
        $this->assert($quality->getResult()->getPoints(), 1);
        $this->assert($quality->getResult()->getMessage(), 'non-strict');
        $this->assert($quality->getResult()->getPreferred(), 'non-strict');
    }

    function fails() {
        $quality = new Strictness(__CLASS__);
        $quality->assert(new AssesStrictness_BadFoo());
        $this->assert($quality->getResult()->getPoints(), -1);
        $this->assert($quality->getResult()->getMessage(), 'strict');
    }
}

class AssesStrictness_BadFoo extends Foo {
    function bas($arg) {
        throw new \Exception;
    }

}