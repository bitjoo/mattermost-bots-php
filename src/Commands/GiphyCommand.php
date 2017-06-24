<?php
namespace Bitjo\Commands;

use Slim\Http\Request;
use Slim\Http\Response;
use rfreebern\Giphy;
use Monolog\Logger;

class GiphyCommand {

    public function __construct(Logger $logger, Request $request, Response $response, $arguments)
    {
        $this->logger = $logger->withName("GiphyCommand");
        $this->request = $request;
        $this->response = $response;
    }

    public function getResponse() {
        $query = $this->request->getParsedBody()["text"];
        $this->logger->debug("Query: " . $query);

        $giphy = new Giphy();
        $result = $giphy->random($query);

        return $this->response->withJson(array(
            "response_type" => "in_channel",
            "text" => "**#" . $query . "**\n\n" .
                "![" . $query . "](" . $result->data->image_original_url . " \"" . $query . "\")"
        ));
    }
}