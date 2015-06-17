<?php
namespace org\rtens\isolation;

use org\rtens\isolation\qualities\FakeInjection;
use org\rtens\isolation\qualities\InspectArguments;
use org\rtens\isolation\qualities\LoggerMock;
use org\rtens\isolation\qualities\LoggerStub;
use org\rtens\isolation\qualities\RecursiveFakes;
use org\rtens\isolation\qualities\Strictness;
use org\rtens\isolation\qualities\StubExpectMixUp;
use org\rtens\isolation\qualities\TestStyle;
use org\rtens\isolation\qualities\TypeCompliance;
use org\rtens\isolation\qualities\VerifyAll;
use org\rtens\isolation\qualities\VerifyByDefault;
use org\rtens\isolation\qualities\VerifySingleCall;

interface Assessment {

    public function strictness(Strictness $quality);

    public function recursiveFakes(RecursiveFakes $quality);

    public function stubExpectMixUp(StubExpectMixUp $quality);

    public function verifySingleCall(VerifySingleCall $quality);

    public function verifyByDefault(VerifyByDefault $quality);

    public function verifyAll(VerifyAll $quality);

    public function fakeInjection(FakeInjection $quality);

    public function typeCompliance(TypeCompliance $quality);

    public function testStyle(TestStyle $quality);

    public function inspectArguments(InspectArguments $quality);

    public function loggerMock(LoggerMock $quality);

    public function loggerStub(LoggerStub $quality);
}