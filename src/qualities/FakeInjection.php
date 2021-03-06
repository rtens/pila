<?php
namespace rtens\isolation\qualities;

use rtens\isolation\classes\Bas;
use rtens\isolation\classes\Foo;
use rtens\isolation\Quality;

class FakeInjection extends Quality {

    protected function preferred() {
        return 'yes';
    }

    protected function failed() {
        return 'no';
    }

    protected function description() {
        return 'Are fakes automatically injected as constructor arguments or properties?';
    }

    protected function maxPoints() {
        return 3;
    }

    /**
     * @param Bas|mixed $fake
     */
    public function assert(Bas $fake) {
        if ($fake->foo instanceof Foo) {
            $this->pass();
        } else {
            $this->fail();
        }
    }
}