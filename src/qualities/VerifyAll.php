<?php
namespace org\rtens\isolation\qualities;

use org\rtens\isolation\Quality;

class VerifyAll extends Quality {

    protected function preferred() {
        return 'no';
    }

    protected function failed() {
        return 'yes';
    }

    protected function description() {
        return 'Could you verify all calls if you wanted?';
    }

    protected function maxPoints() {
        return 1;
    }
}