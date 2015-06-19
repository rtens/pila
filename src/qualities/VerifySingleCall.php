<?php
namespace rtens\isolation\qualities;

use rtens\isolation\Quality;

class VerifySingleCall extends Quality {

    protected function preferred() {
        return 'yes';
    }

    protected function failed() {
        return 'no';
    }

    protected function description() {
        return 'When you verify - do you have to verify all or can you verify only a single specific call?';
    }

    protected function maxPoints() {
        return 3;
    }
}