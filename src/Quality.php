<?php
namespace rtens\isolation;

abstract class Quality {

    /** @var object */
    private $library;

    /** @var Result|null */
    private $result;

    public function __construct(Library $library) {
        $this->library = $library;
    }

    abstract protected function preferred();

    abstract protected function failed();

    abstract protected function description();

    abstract protected function maxPoints();

    protected function name() {
        return $this->className(get_class($this));
    }

    public function pass($message = null) {
        $this->result = $this->score(1, $message ?: $this->preferred());
    }

    public function fail($message = null) {
        $this->result = $this->score(0, $message ?: $this->failed());
    }

    public function partial($percentage, $message) {
        $this->result = $this->score($percentage, $message);
    }

    private function score($percentage, $message, $maxPoints = null) {
        return new Result(
            $this->library,
            $this->name(),
            $this->description(),
            $this->preferred(),
            $percentage * $this->maxPoints(),
            is_null($maxPoints) ? $this->maxPoints() : $maxPoints,
            $message
        );
    }

    /**
     * @return Result
     */
    public function getResult() {
        return $this->result ?: $this->score(0, 'not assessed', 0);
    }

    private function className($class) {
        $parts = explode('\\', $class);
        return strtolower(preg_replace('/([a-zA-Z])([A-Z])/', '$1 $2', end($parts)));
    }
}