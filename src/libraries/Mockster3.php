<?php
namespace rtens\isolation\libraries;

use rtens\isolation\assessments\AdvancedQualities;
use rtens\isolation\assessments\BaseQualities;
use rtens\isolation\assessments\EasOfUse;
use rtens\isolation\classes\Bar;
use rtens\isolation\classes\Bas;
use rtens\isolation\classes\Foo;
use rtens\isolation\classes\Logger;
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
use rtens\mockster\arguments\Argument;
use rtens\mockster\MockProvider;
use rtens\mockster\Mockster;
use watoki\factory\Factory;

class Mockster3 implements Library, BaseQualities, AdvancedQualities, EasOfUse {

    public function name() {
        return 'mockster';
    }

    public function url() {
        return 'http://github.com/rtens/mockster';
    }

    public function strictness(Strictness $quality) {
        /** @var Foo|Mockster $foo */
        $foo = new Mockster(Foo::class);
        Mockster::stub($foo->bas("meh"))->will()->return_("foo");
        $quality->assert($foo->mock());
    }

    public function recursiveFakes(RecursiveFakes $quality) {
        $mock = (new Mockster(Bar::class))->mock();

        $quality->assert($mock);
    }

    public function stubExpectMixUp(StubExpectMixUp $quality) {
        /** @var Mockster|Foo $foo */
        $foo = new Mockster(Foo::class);

        // Stubbing
        Mockster::stub($foo->bar())->will()->return_('foo');

        // Asserting expectations
        Mockster::stub($foo->bar())->has()->beenCalled();

        $quality->pass();
    }

    public function verifySingleCall(VerifySingleCall $quality) {
        /** @var Mockster|Foo $foo */
        $foo = new Mockster(Foo::class);

        /** @var Foo $mock */
        $mock = $foo->mock();

        $mock->bar();
        $mock->baa();
        $mock->baa();

        assert(Mockster::stub($foo->bar())->has()->beenCalled());
        assert(!Mockster::stub($foo->baa())->has()->beenCalled(1));
        assert(!Mockster::stub($foo->bas(null))->has()->beenCalled(1));

        $quality->pass();
    }

    public function verifyByDefault(VerifyByDefault $quality) {
        // No way of defining expectations that could be verified
        $quality->pass();
    }

    public function verifyAll(VerifyAll $quality) {
        // No way to verify all
        $quality->pass();
    }

    public function fakeInjection(FakeInjection $quality) {
        $mock = (new Mockster(Bas::class, $this->createFactory()))->uut();

        $quality->assert($mock);
    }

    public function typeCompliance(TypeCompliance $quality) {
        $quality->pass('with Mockster::stub()');
    }

    public function testStyle(TestStyle $quality) {
        /** @var Mockster|Foo $foo */
        $foo = new Mockster(Foo::class);

        // arrange
        Mockster::stub($foo->baa())->will()->return_('foo');

        // act
        $foo->mock()->baa();

        // assert
        assert(Mockster::stub($foo->baa())->has()->beenCalled());
        assert(Mockster::stub($foo->baa())->has()->inCall(0)->returned() == 'foo');

        $quality->pass();
    }

    public function inspectArguments(InspectArguments $quality) {
        /** @var Mockster|Foo $foo */
        $foo = new Mockster(Foo::class);

        /** @var Foo $mock */
        $mock = $foo->mock();
        $mock->bas(new Bar("uno", "dos"));

        assert(Mockster::stub($foo->bas(Argument::any()))->has()->inCall(0)->argument("arg")->one == "uno");
        assert(Mockster::stub($foo->bas(Argument::any()))->has()->inCall(0)->argument("arg")->two == "dos");

        $quality->pass('with spying');
    }

    public function loggerMock(LoggerMock $quality) {
        /** @var Mockster|Logger $logger */
        $logger = new Mockster(Logger::class);

        /** @var Logger $mock */
        $mock = $logger->mock();
        $mock->log("foo bar bas");

        // either
        assert(Mockster::stub($logger->log(Argument::contains('bar')))->has()->beenCalled());
        // or
        assert(Mockster::stub($logger->log(Argument::regex('/bar/')))->has()->beenCalled());

        $quality->pass();
    }

    public function loggerStub(LoggerStub $quality) {
        /** @var Mockster|Service $service */
        $service = new Mockster(Service::class, $this->createFactory());

        Mockster::stub($service->logger->log(Argument::any()))->will()->throw_(new \InvalidArgumentException("Oh no"));

        /** @var Service $mock */
        $mock = $service->uut();
        $mock->doStuff();

        assert(Mockster::stub($service->mailer->mail("Logger failed: Oh no"))->has()->beenCalled());

        $quality->pass();
    }

    /**
     * @return Factory
     */
    private function createFactory() {
        $factory = new Factory();
        $provider = new MockProvider($factory);
        $factory->setProvider('StdClass', $provider);

        $provider->setParameterFilter(function () {
            return true;
        });
        return $factory;
    }
}