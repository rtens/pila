<?php
namespace org\rtens\isolation\qualities;

use org\rtens\isolation\Quality;

class VerifyByDefault extends Quality {

    protected function preferred() {
        return 'no';
    }

    protected function failed() {
        return 'yes';
    }

    protected function description() {
        return 'Does the library verify by default?';
    }

    protected function maxPoints() {
        return 1;
    }
}