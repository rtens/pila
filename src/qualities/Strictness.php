<?php
namespace org\rtens\isolation\qualities;

use org\rtens\isolation\classes\Foo;
use org\rtens\isolation\Quality;

class Strictness extends Quality {

    protected function preferred() {
        return 'non-strict';
    }

    protected function failed() {
        return 'strict';
    }

    protected function description() {
        return "The ability to make non-anticipated method invocation on fakes.";
    }

    protected function maxPoints() {
        return 2;
    }

    /**
     * All methods of Foo should be able to be invoked without throwing an exception.
     *
     * @param Foo|mixed $fake
     * @param null $failure
     */
    public function assert(Foo $fake, $failure = null) {
        try {
            $fake->baa();
            $fake->bar();
            $fake->bas("one");
            $fake->bas("two");

            $this->pass();
        } catch (\Exception $e) {
            $this->fail($failure);
        }
    }
}