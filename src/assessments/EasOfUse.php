<?php
namespace rtens\isolation\assessments;

use rtens\isolation\qualities\InspectArguments;
use rtens\isolation\qualities\LoggerMock;
use rtens\isolation\qualities\LoggerStub;

interface EasOfUse {

    public function inspectArguments(InspectArguments $quality);

    public function loggerMock(LoggerMock $quality);

    public function loggerStub(LoggerStub $quality);

}