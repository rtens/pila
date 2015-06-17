<?php
namespace org\rtens\isolation\qualities;

use org\rtens\isolation\Quality;

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
}