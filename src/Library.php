<?php
namespace org\rtens\isolation;

interface Library {

    /**
     * @return string
     */
    public function name();

    /**
     * @return string
     */
    public function url();
}