<?php
namespace spec\rtens\isolation;

use rtens\isolation\classes\Foo;
use rtens\isolation\Library;
use rtens\isolation\qualities\Strictness;
use rtens\scrut\tests\statics\StaticTestSuite;

class AssesStrictness extends StaticTestSuite {

    function passes() {
        $quality = new Strictness(new AssesStrictness_Library());
        $quality->assert(new Foo());
        $this->assert($quality->getResult()->getPoints(), 2);
        $this->assert($quality->getResult()->getMessage(), 'non-strict');
        $this->assert($quality->getResult()->getPreferred(), 'non-strict');
    }

    function fails() {
        $quality = new Strictness(new AssesStrictness_Library());
        $quality->assert(new AssesStrictness_BadFoo());
        $this->assert($quality->getResult()->getPoints(), 0);
        $this->assert($quality->getResult()->getMessage(), 'strict');
    }
}

class AssesStrictness_Library implements Library {

    public function name() {
    }

    public function url() {
    }
}

class AssesStrictness_BadFoo extends Foo {
    function bas($arg) {
        throw new \Exception;
    }

}