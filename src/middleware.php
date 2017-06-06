<?php
use Bitjo\Middleware\TokenGuard;

$container = $app->getContainer();

$app->add(new TokenGuard($container));
