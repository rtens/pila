<?php
namespace rtens\isolation\qualities;

use rtens\isolation\classes\Bar;
use rtens\isolation\classes\Foo;
use rtens\isolation\Quality;
use rtens\scrut\Assert;

class RecursiveFakes extends Quality {

    protected function preferred() {
        return 'yes';
    }

    protected function failed() {
        return 'no';
    }

    protected function description() {
        return 'Fake methods should be default return fakes according to their return type hints, recursively.';
    }

    protected function maxPoints() {
        return 1;
    }

    /**
     * @param Bar|mixed $bar
     */
    public function assert(Bar $bar) {
        $assert = new Assert();

        try {
            $assert($bar->returnFoo() instanceof Foo);
            $assert($bar->returnBar() instanceof Bar);
            $assert($bar->returnBar()->returnBar() instanceof Bar);
            $assert($bar->returnBar()->returnBar()->returnBar() instanceof Bar);

            $this->pass();
        } catch (\Exception $e) {
            $this->fail();
        }
    }
}