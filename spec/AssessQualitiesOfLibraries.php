<?php
namespace spec\org\rtens\isolation;

use org\rtens\isolation\Library;
use org\rtens\isolation\Runner;
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
    }

    function findAllLibrariesInFolder() {
        $this->givenTheLibrary_Assessing_In('AFoo', '', 'folder/SomeFile.php');
        $this->givenTheLibrary_Assessing_In('AnotherFoo', '', 'folder/OtherFile.php');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 2);
        $this->assertContains($results, $this->map->getLibraryName(), 'AFoo library');
        $this->assertContains($results, $this->map->getLibraryName(), 'AnotherFoo library');
    }

    function collectResultsWithInformationAboutQuality() {
        $this->givenTheLibrary_Assessing('SomeFoo', '');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getQuality(), 'some quality');
        $this->assert($results[0]->getDescription(), 'some description');
        $this->assert($results[0]->getPreferred(), 'preferred thing');
    }

    function defaultResultIsNotAssessed() {
        $this->givenTheLibrary_Assessing('DefaultIsNeutral', '');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), 0);
        $this->assert($results[0]->getMaxPoints(), 0);
        $this->assert($results[0]->getMessage(), 'not assessed');
    }

    function positiveResultWithDefaultMessage() {
        $this->givenTheLibrary_Assessing('PositiveDefault', '$quality->pass();');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), 10);
        $this->assert($results[0]->getMessage(), 'preferred thing');
    }

    function positiveResultWithCustomMessage() {
        $this->givenTheLibrary_Assessing('PositiveCustom', '$quality->pass("some message");');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), 10);
        $this->assert($results[0]->getMessage(), 'some message');
    }

    function neutralResult() {
        $this->givenTheLibrary_Assessing('Neutral', '$quality->partial(.8, "some message");');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), 8);
        $this->assert($results[0]->getMessage(), 'some message');
    }

    function negativeResultWithDefaultMessage() {
        $this->givenTheLibrary_Assessing('NegativeDefault', '$quality->fail();');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), 0);
        $this->assert($results[0]->getMessage(), 'bad thing');
    }

    function negativeResultWithCustomMessage() {
        $this->givenTheLibrary_Assessing('NegativeCustom', '$quality->fail("some message");');

        $results = $this->runWithFolder('folder');

        $this->assert->size($results, 1);
        $this->assert($results[0]->getPoints(), 0);
        $this->assert($results[0]->getMessage(), 'some message');
    }

    private function givenTheLibrary_Assessing($name, $code) {
        $this->givenTheLibrary_Assessing_In($name, $code, 'folder/SomeFile.php');
    }

    private function givenTheLibrary_Assessing_In($name, $code, $file) {
        $this->files->givenTheFile_Containing($file, '<?php
            class ' . $name . ' implements ' . Library::class . ' {

                public function name() {
                    return "' . $name . ' library";
                }

                public function url() {
                    return "http://example.com";
                }

                function someQuality(' . AssessQualitiesOfLibraries_Quality::class . ' $quality) {
                    ' . $code . '
                }
            }');
    }

    /**
     * @param $folder
     * @return array|\org\rtens\isolation\Result[]
     */
    private function runWithFolder($folder) {
        $runner = new Runner($this->files->fullPath($folder));
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

class AssessQualitiesOfLibraries_Quality extends Quality {

    protected function name() {
        return 'some quality';
    }

    protected function preferred() {
        return 'preferred thing';
    }

    protected function failed() {
        return 'bad thing';
    }

    protected function description() {
        return 'some description';
    }

    protected function maxPoints() {
        return 10;
    }
}

class AssessQualitiesOfLibraries_ResultMapper {
    function __call($name, $args) {
        return $name;
    }
}