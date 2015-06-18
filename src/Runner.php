<?php
namespace org\rtens\isolation;

class Runner {

    private $directory;

    function __construct($directory) {
        $this->directory = $directory;
    }

    /**
     * @return array|\org\rtens\isolation\Result[]
     */
    public function run() {
        $results = [];
        foreach ($this->findClassesInLibrariesFolder() as $libraryClass) {
            $results = array_merge($results,
                $this->assessLibrary(new $libraryClass)
            );
        }
        return $results;
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
        foreach (get_class_methods(get_class($library)) as $method) {
            if ((new \ReflectionMethod($library, $method))->getParameters()) {
                $results[] = $this->assessQuality($library, $method);
            }
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

        try {
            set_error_handler([$this, 'handleError'], E_ALL);
            $library->$method($quality);
            restore_error_handler();
        } catch (\Exception $e) {
            $quality->fail($e->getMessage());
        }

        $result = $quality->getResult();
        return $result;
    }

    public function handleError($code, $message, $file, $line) {
        if (error_reporting() == 0) return;
        throw new \RuntimeException($message . ' in ' . $file . ':' . $line, $code);
    }

    /**
     * @param object $library
     * @param string $method
     * @return Quality
     */
    private function getQualityParameter($library, $method) {
        $qualityClass = (new \ReflectionMethod($library, $method))->getParameters()[0]->getClass()->getName();
        return new $qualityClass($library);
    }
}