<?php
namespace rtens\isolation\qualities;

use rtens\isolation\Quality;

class StubExpectMixUp extends Quality {

    protected function preferred() {
        return 'no';
    }

    protected function failed() {
        return 'yes';
    }

    protected function description() {
        return 'Does stubbing a return value feel like setting an expectation?';
    }

    protected function maxPoints() {
        return 3;
    }
}