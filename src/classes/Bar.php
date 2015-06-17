<?php
namespace org\rtens\isolation\classes;

class Bar {

    public $one;
    public $two;

    function __construct($one = null, $two = null) {
        $this->one = $one;
        $this->two = $two;
    }

    /**
     * @return Foo
     */
    function returnFoo() {
        return null;
    }

    /**
     * @return Bar
     */
    function returnBar() {
        return null;
    }
}