<?php
namespace org\rtens\isolation\qualities;

use org\rtens\isolation\Quality;

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
}