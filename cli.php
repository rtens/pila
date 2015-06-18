<?php

include __DIR__ . "/vendor/autoload.php";

$results = (new \org\rtens\isolation\Runner(__DIR__ . '/src/libraries'))->run();

if (!$results) {
    echo "No results" . PHP_EOL;
    exit();
}

$accessors = [
    'Library' => 'getLibraryName',
    'Quality' => 'getQuality',
    'Weight' => 'getMaxPoints',
    'Points' => 'getPoints',
    'Message' => 'getMessage'
];

$width = [
    'Library' => 12,
    'Quality' => 20,
    'Weight' => 6,
    'Points' => 6,
    'Message' => 100
];

$scores = [];
$maxScore = [];

foreach ($results as $result) {
    if (!array_key_exists($result->getLibraryName(), $scores)) {
        $scores[$result->getLibraryName()] = 0;
        $maxScore[$result->getLibraryName()] = 0;
    }
    $scores[$result->getLibraryName()] += $result->getPoints();
    $maxScore[$result->getLibraryName()] += $result->getMaxPoints();
}
krsort($scores);

print(PHP_EOL);
print('High score list' . PHP_EOL);
print('~~~~~~~~~~~~~~~' . PHP_EOL . PHP_EOL);

foreach ($scores as $library => $score) {
    printf('%-12s %3d / %d' . PHP_EOL, $library, $score, $maxScore[$library]);
}

print(PHP_EOL);
print(PHP_EOL);
print('Detailed assessment' . PHP_EOL);
print('~~~~~~~~~~~~~~~~~~~' . PHP_EOL . PHP_EOL);

foreach ($accessors as $library => $accessor) {
    printf('%-' . $width[$library] . 's | ', $library);
}

print(PHP_EOL);

foreach ($accessors as $library => $accessor) {
    print(str_repeat('-', $width[$library]) . '-+-');
}

foreach ($results as $result) {
    print(PHP_EOL);
    foreach ($accessors as $library => $accessor) {
        printf("%-" . $width[$library] . "s | ", $result->$accessor());
    }
}

print(PHP_EOL);