<?php
namespace rtens\isolation;

use rtens\isolation\qualities\FakeInjection;
use rtens\isolation\qualities\InspectArguments;
use rtens\isolation\qualities\LoggerMock;
use rtens\isolation\qualities\LoggerStub;
use rtens\isolation\qualities\RecursiveFakes;
use rtens\isolation\qualities\Strictness;
use rtens\isolation\qualities\StubExpectMixUp;
use rtens\isolation\qualities\TestStyle;
use rtens\isolation\qualities\TypeCompliance;
use rtens\isolation\qualities\VerifyAll;
use rtens\isolation\qualities\VerifyByDefault;
use rtens\isolation\qualities\VerifySingleCall;

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