<?php
namespace rtens\isolation\libraries;

use Prophecy\Argument;
use Prophecy\Exception\Prediction\AggregateException;
use Prophecy\Exception\Prediction\NoCallsException;
use Prophecy\Exception\Prediction\UnexpectedCallsCountException;
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
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class Prophecy implements Library, BaseQualities, AdvancedQualities, EasOfUse {

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
        /** @var Foo|ObjectProphecy $foo */
        $foo = (new Prophet())->prophesize(Foo::class);

        /** @var \Prophecy\Prophecy\MethodProphecy $bar */
        $bar = $foo->bar();

        // Stubbing
        $bar->willReturn('foo');

        // Expectations
        $bar->shouldBeCalled();

        $quality->pass();
    }

    public function verifySingleCall(VerifySingleCall $quality) {
        /** @var Foo|ObjectProphecy $foo */
        $foo = (new Prophet())->prophesize(Foo::class);

        /** @var Foo $fake */
        $fake = $foo->reveal();

        $fake->bar();
        $fake->baa();
        $fake->baa();

        self::method($foo->bar())->shouldHaveBeenCalled();

        $this->assertThrows(UnexpectedCallsCountException::class, function() use ($foo) {
            self::method($foo->baa())->shouldHaveBeenCalledTimes(1);
        });

        $this->assertThrows(NoCallsException::class, function() use ($foo) {
            self::method($foo->bas(Argument::any()))->shouldHaveBeenCalled();
        });

        $quality->pass();
    }

    public function verifyByDefault(VerifyByDefault $quality) {
        /** @var Foo|ObjectProphecy $foo */
        $foo = (new Prophet())->prophesize(Foo::class);

        self::method($foo->bar())->shouldBeCalled();

        $quality->pass();
    }

    public function verifyAll(VerifyAll $quality) {
        $prophet = new Prophet();

        /** @var Foo|ObjectProphecy $foo */
        $foo = $prophet->prophesize(Foo::class);

        self::method($foo->bar())->shouldBeCalled();

        $this->assertThrows(AggregateException::class, function () use ($prophet) {
            $prophet->checkPredictions();
        });

        $quality->fail();
    }

    public function fakeInjection(FakeInjection $quality) {
        /** @var Bas $fake */
        $fake = (new Prophet())->prophesize(Bas::class)->reveal();

        $quality->assert($fake);
    }

    public function typeCompliance(TypeCompliance $quality) {
        $quality->partial(.5, 'with type hints');
    }

    public function testStyle(TestStyle $quality) {
        /** @var Foo|ObjectProphecy $foo */
        $foo = (new Prophet())->prophesize(Foo::class);

        // arrange
        self::method($foo->baa())->willReturn('foo');

        // act
        $foo->reveal()->baa();

        // assert
        self::method($foo->baa())->shouldHaveBeenCalled();

        //// but it's also possible to do /////

        // arrange
        self::method($foo->baa())->willReturn('foo');

        // assert
        self::method($foo->baa())->shouldBeCalled();

        // act
        $foo->reveal()->baa();

        $quality->partial(3 / 4, 'possible to arrange-assert-act');
    }

    public function inspectArguments(InspectArguments $quality) {
        /** @var Foo|ObjectProphecy $foo */
        $foo = (new Prophet())->prophesize(Foo::class);

        /** @var Foo $mock */
        $mock = $foo->reveal();
        $mock->bas(new Bar("uno", "dos"));

        self::method($foo->bas(Argument::any()))->shouldHave(function ($calls) {
            /** @var \Prophecy\Call\Call $call */
            $call = $calls[0];
            assert($call->getArguments()[0]->one == "uno");
            assert($call->getArguments()[0]->two == "dos");
        });

        $quality->partial(3 / 6, "only with callback");
    }

    public function loggerMock(LoggerMock $quality) {
        /** @var ObjectProphecy|Logger $logger */
        $logger = (new Prophet())->prophesize(Logger::class);

        /** @var Logger $mock */
        $mock = $logger->reveal();

        $mock->log("foo bar bas");

        // either
        self::method($logger->log(Argument::containingString('bar')))->shouldHaveBeenCalled();
        // or //
        self::method($logger->log(Argument::that(function ($arg) {
            return strpos($arg, 'bar') !== false;
        })))->shouldHaveBeenCalled();

        $quality->pass();
    }

    public function loggerStub(LoggerStub $quality) {
        /** @var Logger|ObjectProphecy $logger */
        $logger = (new Prophet())->prophesize(Logger::class);
        /** @var Mailer|ObjectProphecy $mailer */
        $mailer = (new Prophet())->prophesize(Mailer::class);

        self::method($logger->log(Argument::any()))->willThrow(new \InvalidArgumentException("Oh no"));

        $service = new Service($logger->reveal(), $mailer->reveal());
        $service->doStuff();

        self::method($mailer->mail("Logger failed: Oh no"))->shouldHaveBeenCalled();

        $quality->pass();
    }

    private function assertThrows($exception, $callback) {
        try {
            $callback();
        } catch (\Exception $e) {
            assert(is_a($e, $exception));
        }
    }

    /**
     * @param mixed $method
     * @return \Prophecy\Prophecy\MethodProphecy
     */
    private static function method($method) {
        return $method;
    }
}