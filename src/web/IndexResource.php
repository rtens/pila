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
        return array_values(array_unique(array_map(function (Result $result) {
            return $result->getLibrary();
        }, $results)));
    }

    /**
     * @param \org\rtens\isolation\Result[] $results
     * @return array
     */
    private function assembleResults($results) {
        $libraries = $this->assembleLibraries($results);

        $qualityResults = [];

        foreach ($results as $result) {
            $key = $result->getQuality();
            if (!array_key_exists($key, $qualityResults)) {
                $qualityResults[$key] = [
                    'quality' => [
                        'name' => $result->getQuality(),
                        'title' => $result->getDescription(),
                    ],
                    'preferred' => $result->getPreferred(),
                    'result' => []
                ];
            }

            $qualityResults[$key]['result'][array_search($result->getLibrary(), $libraries)] = [
                'class' => $result->getPoints() > 0 ? 'success' : ($result->getPoints() < 0 ? 'danger' : 'warning'),
                'message' => $result->getMessage()
            ];
        }

        return array_values($qualityResults);
    }
}