<?php
namespace rtens\isolation\assessments;

use rtens\isolation\qualities\FakeInjection;
use rtens\isolation\qualities\TypeCompliance;

interface AdvancedQualities {

    public function typeCompliance(TypeCompliance $quality);

    public function fakeInjection(FakeInjection $quality);

}