<?php
namespace rtens\isolation\qualities;

use rtens\isolation\Quality;

class TypeCompliance extends Quality {

    protected function preferred() {
        return 'yes';
    }

    protected function failed() {
        return 'no';
    }

    protected function description() {
        return 'Can you use the library without loosing type compliance (i.e. code navigation and auto-complete)?';
    }

    protected function maxPoints() {
        return 2;
    }
}