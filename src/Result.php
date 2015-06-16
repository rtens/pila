<?php
namespace org\rtens\isolation;

class Result {

    private $quality;
    private $description;
    private $preferred;
    private $library;
    private $points = 0;
    private $message = 'Not assessed';

    function __construct($library, $quality, $description, $preferred, $points, $message) {
        $this->library = $library;
        $this->quality = $quality;
        $this->points = $points;
        $this->message = $message;
        $this->description = $description;
        $this->preferred = $preferred;
    }

    /**
     * @return int
     */
    public function getPoints() {
        return $this->points;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return string
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

}