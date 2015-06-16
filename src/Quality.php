<?php
namespace org\rtens\isolation;

abstract class Quality {

    /** @var string */
    private $library;

    /** @var Result|null */
    private $result;

    public function __construct($libraryClass) {
        $this->library = $libraryClass;
    }

    abstract protected function preferred();

    abstract protected function failed();

    abstract protected function description();

    protected function name() {
        return $this->className(get_class($this));
    }

    private function library() {
        return $this->className($this->library);
    }

    public function pass($message = null) {
        $this->result = $this->result(1, $message ?: $this->preferred());
    }

    public function fail($message = null) {
        $this->result = $this->result(-1, $message ?: $this->failed());
    }

    public function neutral($message) {
        $this->result = $this->result(0, $message);
    }

    private function result($points, $message) {
        return new Result($this->library(), $this->name(), $this->description(), $this->preferred(), $points, $message);
    }

    /**
     * @return Result
     */
    public function getResult() {
        return $this->result ?: $this->result(0, 'not assessed');
    }

    private function className($class) {
        $parts = explode('\\', $class);
        return strtolower(preg_replace('/([a-zA-Z])([A-Z])/', '$1 $2', end($parts)));
    }
}