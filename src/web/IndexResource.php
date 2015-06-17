<?php
namespace org\rtens\isolation\web;

use org\rtens\isolation\Library;
use org\rtens\isolation\Result;
use org\rtens\isolation\Runner;

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
     * @param \org\rtens\isolation\Result[] $results
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
     * @param \org\rtens\isolation\Result[] $results
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
                'class' => $result->getPoints() == $result->getMaxPoints() ? ($result->getPoints() ? 'success' : 'warning') : 'danger',
                'message' => $result->getMessage(),
                'title' => $result->getPoints() . '/' . $result->getMaxPoints()
            ];
        }

        return array_values($qualityResults);
    }
}