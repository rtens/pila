<?php
namespace rtens\isolation\web;

use rtens\isolation\Library;
use rtens\isolation\Result;
use rtens\isolation\Runner;

class IndexResource extends \watoki\curir\Container {

    public function doGet() {
        $runner = new Runner(__DIR__ . '/../libraries', Library::class);
        $results = $runner->run();

        return [
            'library' => $this->assembleLibraries($results),
            'result' => $this->assembleResults($results)
        ];
    }

    /**
     * @param \rtens\isolation\Result[] $results
     * @return array
     */
    private function assembleLibraries($results) {
        return array_map(function (Library $library) {
            return [
                'name' => $library->name(),
                'code' => ['href' => $this->getGitHubUrl($library)],
                'url' => ['href' => $library->url()],
            ];
        }, $this->readLibraries($results));
    }

    private function getGitHubUrl(Library $library) {
        $classPath = substr(get_class($library), strlen('org/rtens/isolation/'));
        return 'https://github.com/rtens/pila/blob/master/src/' . $classPath . '.php';
    }

    /**
     * @param Result[] $results
     * @return array|Library[]
     */
    private function readLibraries($results) {
        $libraries = [];
        foreach ($results as $result) {
            $libraries[$result->getLibraryName()] = $result->getLibrary();
        }
        return array_values($libraries);
    }

    /**
     * @param \rtens\isolation\Result[] $results
     * @return array
     */
    private function assembleResults($results) {
        $libraries = $this->readLibraries($results);

        $qualityResults = [];

        foreach ($results as $result) {
            $key = $result->getQuality();
            if (!array_key_exists($key, $qualityResults)) {
                $qualityResults[$key] = [
                    'quality' => [
                        'name' => $result->getQuality(),
                        'title' => $result->getDescription(),
                        'maxScore' => $result->getMaxPoints()
                    ],
                    'preferred' => $result->getPreferred(),
                    'result' => []
                ];
            }

            $qualityResults[$key]['result'][array_search($result->getLibrary(), $libraries)] = [
                'class' => $this->determineClass($result),
                'message' => $result->getMessage(),
                'title' => $result->getPoints() . '/' . $result->getMaxPoints()
            ];
        }

        return array_values($qualityResults);
    }

    private function determineClass(Result $result) {
        if ($result->getMaxPoints() == 0) {
            return 'warning';
        } else if ($result->getPoints() == 0) {
            return 'danger';
        } else if ($result->getPoints() < $result->getMaxPoints()) {
            return 'warning';
        } else {
            return 'success';
        }
    }
}