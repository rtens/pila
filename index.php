<?php

use rtens\isolation\web\IndexResource;
use watoki\curir\rendering\adapter\TempanRenderer;
use watoki\curir\WebDelivery;

include __DIR__ . "/vendor/autoload.php";

WebDelivery::quickResponse(
    IndexResource::class,
    WebDelivery::init(new TempanRenderer()));