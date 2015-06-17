<?php
namespace org\rtens\isolation\qualities;

use org\rtens\isolation\classes\Bas;
use org\rtens\isolation\classes\Foo;
use org\rtens\isolation\Quality;

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