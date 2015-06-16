<?php
namespace org\rtens\isolation\cli;

use org\rtens\isolation\Quality;

class Runner {

    private $directory;
    private $baseClass;

    function __construct($directory, $baseClass) {
        $this->directory = $directory;
        $this->baseClass = $baseClass;
    }

    /**
     * @return array|\org\rtens\isolation\Result[]
     */
    public function run() {
        $results = [];
        foreach ($this->findLibraryClasses() as $libraryClass) {
            $results = array_merge($results,
                $this->assessLibrary(new $libraryClass)
            );
        }
        return $results;
    }

    private function findLibraryClasses() {
        $newClasses = $this->findClassesInLibrariesFolder();

        return array_values(array_filter($newClasses, function ($class) {
            return is_subclass_of($class, $this->baseClass);
        }));
    }

    private function findClassesInLibrariesFolder() {
        $classesBefore = get_declared_classes();
        foreach (glob($this->directory . '/*.php') as $file) {
            require_once $file;
        }
        return array_diff(get_declared_classes(), $classesBefore);
    }

    private function assessLibrary($library) {
        $results = [];
        foreach (get_class_methods($this->baseClass) as $method) {
            $results[] = $this->assessQuality($library, $method);
        }
        return $results;
    }

    /**
     * @param object $library
     * @param string $method
     * @return \org\rtens\isolation\Result
     */
    private function assessQuality($library, $method) {
        $quality = $this->getQualityParameter($library, $method);
        $library->$method($quality);
        $result = $quality->getResult();
        return $result;
    }

    /**
     * @param object $library
     * @param string $method
     * @return Quality
     */
    private function getQualityParameter($library, $method) {
        $qualityClass = (new \ReflectionMethod($library, $method))->getParameters()[0]->getClass()->getName();
        return new $qualityClass(get_class($library));
    }
}