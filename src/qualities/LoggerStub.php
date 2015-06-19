<?php
namespace rtens\isolation\qualities;

use rtens\isolation\Quality;

class LoggerStub extends Quality {

    protected function preferred() {
        return 'easy';
    }

    protected function failed() {
        return 'not ' . $this->preferred();
    }

    protected function description() {
        return 'How easily can you create a stub logger that simulates an exception, and a mock service that gets a message containing string if the logger throws?';
    }

    protected function maxPoints() {
        return 8;
    }
}