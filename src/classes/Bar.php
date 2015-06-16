<?php
namespace org\rtens\isolation\classes;

class Bar {

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