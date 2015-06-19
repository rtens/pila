<?php
namespace rtens\isolation\qualities;

use rtens\isolation\Quality;

class InspectArguments extends Quality {

    protected function preferred() {
        return 'easy';
    }

    protected function failed() {
        return 'not ' . $this->preferred();
    }

    protected function description() {
        return 'How simple are the matchers for expected parameters to write?';
    }

    protected function maxPoints() {
        return 6;
    }
}