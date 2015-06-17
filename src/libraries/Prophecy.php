<?php
namespace org\rtens\isolation\libraries;

use org\rtens\isolation\Assessment;
use org\rtens\isolation\classes\Bar;
use org\rtens\isolation\classes\Foo;
use org\rtens\isolation\Library;
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
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class Prophecy implements Library, Assessment {

    public function name() {
        return "Prophecy";
    }

    public function url() {
        return 'http://github.com/phpspec/prophecy';
    }

    public function strictness(Strictness $quality) {
        /** @var Foo|ObjectProphecy $foo */
        $foo = (new Prophet())->prophesize(Foo::class);

        /** @var Foo $fake */
        $fake = $foo->reveal();

        // Non-strict here
        $quality->assert($fake);

        /** @var \Prophecy\Prophecy\MethodProphecy $bas */
        $bas = $foo->bas("meh");
        $bas->willReturn("foo");

        // But strict as soon as anything is configured
        $quality->assert($fake, 'strict if anything is stubbed');
    }

    public function recursiveFakes(RecursiveFakes $quality) {
        $fake = (new Prophet())->prophesize(Bar::class)->reveal();

        $quality->assert($fake);
    }

    public function stubExpectMixUp(StubExpectMixUp $quality) {
    }

    public function verifySingleCall(VerifySingleCall $quality) {
    }

    public function verifyByDefault(VerifyByDefault $quality) {
    }

    public function verifyAll(VerifyAll $quality) {
    }

    public function fakeInjection(FakeInjection $quality) {
    }

    public function typeCompliance(TypeCompliance $quality) {
    }

    public function testStyle(TestStyle $quality) {
    }

    public function inspectArguments(InspectArguments $quality) {
    }

    public function loggerMock(LoggerMock $quality) {
    }

    public function loggerStub(LoggerStub $quality) {
    }
}