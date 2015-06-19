<?php
namespace rtens\isolation\assessments;

use rtens\isolation\qualities\RecursiveFakes;
use rtens\isolation\qualities\Strictness;
use rtens\isolation\qualities\StubExpectMixUp;
use rtens\isolation\qualities\TestStyle;
use rtens\isolation\qualities\VerifyAll;
use rtens\isolation\qualities\VerifyByDefault;
use rtens\isolation\qualities\VerifySingleCall;

interface BaseQualities {

    public function strictness(Strictness $quality);

    public function recursiveFakes(RecursiveFakes $quality);

    public function stubExpectMixUp(StubExpectMixUp $quality);

    public function verifySingleCall(VerifySingleCall $quality);

    public function verifyByDefault(VerifyByDefault $quality);

    public function verifyAll(VerifyAll $quality);

    public function testStyle(TestStyle $quality);
}