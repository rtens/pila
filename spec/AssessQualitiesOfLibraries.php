<?php
namespace spec\org\rtens\isolation;

use org\rtens\isolation\cli\Runner;
use org\rtens\isolation\Quality;
use org\rtens\isolation\Result;
use rtens\scrut\tests\statics\StaticTestSuite;

/**
 * @property \rtens\scrut\fixtures\FilesFixture files <-
 */
class AssessQualitiesOfLibraries extends StaticTestSuite {

    /** @var Result */
    private $map;

    protected function before() {
        $this->map = new AssessQualitiesOfLibraries_ResultMapper();

        if (!class_exists('SomeQuality')) {
            eval('class Foo {
                function bar(SomeQuality $quality) {}
            }');

            eval('class SomeQuality extends ' . Quality::class . '{
                protected function preferred() {
                    return "preferred thing";
                }

                protected function failed() {
                    return "bad thing";
                }

                protected function description() {
                    return "some description";
                }
            }');
        }
    }


    function findAllLibrariesInFolder() {
        $this->files->givenTheFile_Containing('folder/SomeFile.php', '<?php
            class AFoo extends Foo {}');
        $this->files->givenTheFile_Containing('folder/OtherFile.php', '<?php
            class AnotherFoo extends Foo {}
            class NotAFoo {}');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 2);
        $this->assertContains($results, $this->map->getLibrary(), 'a foo');
        $this->assertContains($results, $this->map->getLibrary(), 'another foo');
    }

    function collectResultsWithInformationAboutQuality() {
        $this->files->givenTheFile_Containing('folder/SomeFile.php', '<?php
            class SomeFoo extends Foo {}');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getQuality(), 'some quality');
        $this->assert($results[0]->getDescription(), 'some description');
        $this->assert($results[0]->getPreferred(), 'preferred thing');
    }

    function defaultResultIsNeutral() {
        $this->files->givenTheFile_Containing('folder/SomeFile.php', '<?php
            class DefaultIsNeutral extends Foo {}');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), 0);
        $this->assert($results[0]->getMessage(), 'not assessed');
    }

    function positiveResultWithDefaultMessage() {
        $this->files->givenTheFile_Containing('folder/SomeFile.php', '<?php
            class PositiveDefault extends Foo {
                function bar(SomeQuality $quality) {
                    $quality->pass();
                }
            }');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), 1);
        $this->assert($results[0]->getMessage(), 'preferred thing');
    }

    function positiveResultWithCustomMessage() {
        $this->files->givenTheFile_Containing('folder/SomeFile.php', '<?php
            class PositiveCustom extends Foo {
                function bar(SomeQuality $quality) {
                    $quality->pass("some message");
                }
            }');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), 1);
        $this->assert($results[0]->getMessage(), 'some message');
    }

    function neutralResult() {
        $this->files->givenTheFile_Containing('folder/SomeFile.php', '<?php
            class Neutral extends Foo {
                function bar(SomeQuality $quality) {
                    $quality->neutral("some message");
                }
            }');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), 0);
        $this->assert($results[0]->getMessage(), 'some message');
    }

    function negativeResultWithDefaultMessage() {
        $this->files->givenTheFile_Containing('folder/SomeFile.php', '<?php
            class NegativeDefault extends Foo {
                function bar(SomeQuality $quality) {
                    $quality->fail();
                }
            }');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), -1);
        $this->assert($results[0]->getMessage(), 'bad thing');
    }

    function negativeResultWithCustomMessage() {
        $this->files->givenTheFile_Containing('folder/SomeFile.php', '<?php
            class NegativeCustom extends Foo {
                function bar(SomeQuality $quality) {
                    $quality->fail("some message");
                }
            }');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), -1);
        $this->assert($results[0]->getMessage(), 'some message');
    }

    /**
     * @param $folder
     * @return array|\org\rtens\isolation\Result[]
     */
    private function runWithFolder($folder) {
        $runner = new Runner($this->files->fullPath($folder), 'Foo');
        $results = $runner->run();
        return $results;
    }

    private function assertContains($haystack, $mapper, $needle) {
        $mapped = array_map(function ($item) use ($mapper) {
            return $item->$mapper();
        }, $haystack);
        $this->assert->contains($mapped, $needle);
    }
}

class AssessQualitiesOfLibraries_ResultMapper {
    function __call($name, $args) {
        return $name;
    }
}