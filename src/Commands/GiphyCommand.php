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
        $result = $giphy->search($query, 50);
        $images = $result->data;

        if (is_array($images) && count($images) > 0) {
            $rand_image_key = array_rand($images, 1);
            $giphyUrl = $images[$rand_image_key]->images->original->url;
        }

        if (isset($giphyUrl)) {
            $responseType = "in_channel";
            $responseText = "**#" . $query . "**\n\n" .
                "![" . $query . "](" . $giphyUrl . " \"" . $query . "\")";
        } else {
            $responseType = "ephemeral";
            $responseText = "No image could be found with your query **" . $query . "**";
        }

        return $this->response->withJson(array(
            "response_type" => $responseType,
            "text" => $responseText
        ));
    }
}