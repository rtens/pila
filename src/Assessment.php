<?php
namespace org\rtens\isolation;

use org\rtens\isolation\qualities\RecursiveFakes;
use org\rtens\isolation\qualities\Strictness;

interface Assessment {

    public function strictness(Strictness $quality);

    public function recursiveFakes(RecursiveFakes $quality);

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