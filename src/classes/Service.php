<?php
namespace org\rtens\isolation\classes;


class Service {

    /** @var Logger */
    public $logger;

    /** @var Mailer */
    public $mailer;

    function __construct(Logger $logger, Mailer $mailer) {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    function doStuff() {
        try {
            $this->logger->log("foo");
        } catch (\InvalidArgumentException $e) {
            $this->mailer->mail("Logger failed: " . $e->getMessage());
        }
    }
}