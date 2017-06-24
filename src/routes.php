<?php
use Bitjo\Commands\GiphyCommand;

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->post('/giphy', function ($request, $response, $args) {
    $giphy = new GiphyCommand($this->logger, $request, $response, $args);

    return $giphy->getResponse();
})->setName('giphy');
