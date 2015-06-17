<?php
namespace org\rtens\isolation\qualities;

use org\rtens\isolation\Quality;

class LoggerMock extends Quality {

    protected function preferred() {
        return 'easy';
    }

    protected function failed() {
        return 'not ' . $this->preferred();
    }

    protected function description() {
        return 'How easily can you create a mock logger that expects a string containing X at least once?';
    }
}