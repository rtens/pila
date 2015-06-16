<?php
namespace org\rtens\isolation\qualities;

use org\rtens\isolation\classes\Bar;
use org\rtens\isolation\classes\Foo;
use org\rtens\isolation\Quality;
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