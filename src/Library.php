<?php
namespace org\rtens\isolation;

use org\rtens\isolation\qualities\Strictness;

interface Library {

    public function strictness(Strictness $strictness);

//    public function recursiveFakes();
//
//    public function stubExpectMixUp();
//
//    public function verifySingleCall();
//
//    public function verifyByDefault();
//
//    public function verifyAll();
//
//    public function fakeInjection();
//
//    public function typeCompliance();
//
//    public function testStyle();
//
//    public function inspectArguments();
//
//    public function loggerMock();
//
//    public function loggerStub();
}