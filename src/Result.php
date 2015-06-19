<?php
namespace rtens\isolation;

class Result {

    private $quality;
    private $description;
    private $preferred;
    private $library;
    private $points = 0;
    private $maxPoints = 0;
    private $message = 'Not assessed';

    function __construct(Library $library, $quality, $description, $preferred, $points, $maxPoints, $message) {
        $this->library = $library;
        $this->quality = $quality;
        $this->points = $points;
        $this->message = $message;
        $this->description = $description;
        $this->preferred = $preferred;
        $this->maxPoints = $maxPoints;
    }

    /**
     * @return int
     */
    public function getPoints() {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getMaxPoints() {
        return $this->maxPoints;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return Library
     */
    public function getLibrary() {
        return $this->library;
    }

    /**
     * @return string
     */
    public function getQuality() {
        return $this->quality;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getPreferred() {
        return $this->preferred;
    }

    /**
     * @return string
     */
    public function getLibraryName() {
        return $this->library->name();
    }

}