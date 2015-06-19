<?php
namespace rtens\isolation\libraries;

use PHPUnit_Framework_Constraint_IsEqual;
use PHPUnit_Framework_MockObject_Generator;
use PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount;
use PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_MockObject_Stub_ReturnCallback;
use rtens\isolation\assessments\AdvancedQualities;
use rtens\isolation\assessments\BaseQualities;
use rtens\isolation\assessments\EasOfUse;
use rtens\isolation\classes\Bar;
use rtens\isolation\classes\Bas;
use rtens\isolation\classes\Foo;
use rtens\isolation\classes\Logger;
use rtens\isolation\classes\Mailer;
use rtens\isolation\classes\Service;
use rtens\isolation\Library;
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

class PhpUnit implements Library, BaseQualities, AdvancedQualities, EasOfUse {

    /**
     * @return string
     */
    public function name() {
        return 'PHPUnit';
    }

    /**
     * @return string
     */
    public function url() {
        return 'https://github.com/sebastianbergmann/phpunit-mock-objects';
    }

    public function strictness(Strictness $quality) {
        /** @var Foo|PHPUnit_Framework_MockObject_MockObject $foo */
        $foo = (new PHPUnit_Framework_MockObject_Generator())->getMock(Foo::class);

        $foo->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce())
            ->method('bas')
            ->willReturn('foo');

        $quality->assert($foo);
    }

    public function recursiveFakes(RecursiveFakes $quality) {
        $mock = (new PHPUnit_Framework_MockObject_Generator())->getMock(Bar::class);

        $quality->assert($mock);
    }

    public function stubExpectMixUp(StubExpectMixUp $quality) {
        /** @var Foo|PHPUnit_Framework_MockObject_MockObject $foo */
        $foo = (new PHPUnit_Framework_MockObject_Generator())->getMock(Foo::class);

        // stubbing
        $foo->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce())
            ->method('bar')
            ->willReturn('foo');

        // expectation
        $foo->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce())
            ->method('bar');

        $quality->fail();
    }

    public function verifySingleCall(VerifySingleCall $quality) {
        // Can't verify single call
        $quality->fail();
    }

    public function verifyByDefault(VerifyByDefault $quality) {
        $quality->fail();
    }

    public function verifyAll(VerifyAll $quality) {
        /** @var Foo|PHPUnit_Framework_MockObject_MockObject $foo */
        $foo = (new PHPUnit_Framework_MockObject_Generator())->getMock(Foo::class);

        $foo->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce())
            ->method('bar');

        $foo->bar();

        $foo->__phpunit_verify();

        $quality->fail();
    }

    public function fakeInjection(FakeInjection $quality) {
        try {
            $mock = (new PHPUnit_Framework_MockObject_Generator())->getMock(Bas::class);
            $quality->assert($mock);
        } catch (\Exception $e) {
            $quality->fail();
        }
    }

    public function typeCompliance(TypeCompliance $quality) {
        $quality->fail();
    }

    public function testStyle(TestStyle $quality) {
        /** @var Foo|PHPUnit_Framework_MockObject_MockObject $foo */
        $foo = (new PHPUnit_Framework_MockObject_Generator())->getMock(Foo::class);

        // arrange
        $foo->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce())
            ->method('bar')
            ->willReturn('foo');

        // assert
        $foo->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce())
            ->method('bar');

        // act
        $foo->bar();

        $quality->fail('arrange-assert-act');
    }

    public function inspectArguments(InspectArguments $quality) {
        /** @var Foo|PHPUnit_Framework_MockObject_MockObject $foo */
        $foo = (new PHPUnit_Framework_MockObject_Generator())->getMock(Foo::class);

        $foo->expects(new PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount())
            ->method('bas')
            ->will(new PHPUnit_Framework_MockObject_Stub_ReturnCallback(function (Bar $arg) {
                assert($arg->one == 'uno');
                assert($arg->two == 'dos');
            }));

        $foo->bas(new Bar("uno", "dos"));

        $foo->__phpunit_verify();

        $quality->partial(4 / 6, "only with callback");
    }

    public function loggerMock(LoggerMock $quality) {
        /** @var Logger|PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = (new PHPUnit_Framework_MockObject_Generator())->getMock(Logger::class);

        $logger->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce())
            ->method('log')
            ->with(new \PHPUnit_Framework_Constraint_StringContains("bar"));

        $logger->log('foo bar bas');

        $logger->__phpunit_verify();

        $quality->partial(3 / 6, 'unreadable');
    }

    public function loggerStub(LoggerStub $quality) {
        /** @var Logger|PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = (new PHPUnit_Framework_MockObject_Generator())->getMock(Logger::class);
        /** @var Mailer|PHPUnit_Framework_MockObject_MockObject $mailer */
        $mailer = (new PHPUnit_Framework_MockObject_Generator())->getMock(Mailer::class);

        $logger->expects(new PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount())
            ->method('log')
            ->willThrowException(new \InvalidArgumentException("Oh no"));

        $mailer->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce())
            ->method('mail')
            ->with(new PHPUnit_Framework_Constraint_IsEqual("Logger failed: Oh no"));

        $service = new Service($logger, $mailer);
        $service->doStuff();

        $logger->__phpunit_verify();

        $quality->partial(4 / 8, 'unreadable');
    }
}