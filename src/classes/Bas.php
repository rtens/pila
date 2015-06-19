<?php
namespace rtens\isolation\classes;

class Bas {

    public $foo;

    function __construct(Foo $foo) {
        $this->foo = $foo;
    }
}