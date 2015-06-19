<?php
namespace rtens\isolation\qualities;

use rtens\isolation\Quality;

class TestStyle extends Quality {

    protected function preferred() {
        return 'arrange-act-assert';
    }

    protected function failed() {
        return 'not ' . $this->preferred();
    }

    protected function description() {
        return 'What is the test style? record replay? arrange-assert-act? arrange-act-assert?';
    }

    protected function maxPoints() {
        return 4;
    }
}