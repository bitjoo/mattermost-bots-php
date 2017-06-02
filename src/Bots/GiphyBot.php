<?php

namespace Bitjo\Bots;

use Slim\Http\Request;
use Slim\Http\Response;
use rfreebern\Giphy;
use Monolog\Logger;



class GiphyBot {

    public function __construct(Logger $logger, Request $request, Response $response, $arguments)
    {
        $this->logger = $logger->withName("GiphyBot");
        $this->request = $request;
        $this->response = $response;
        $this->arguments = $arguments;
    }

    public function getResponse() {
        $giphy = new Giphy();
        $result = "";
        parse_str($this->request->getBody()->getContents(), $params);
        $query = $params["text"];
        $this->logger->debug("Query: " . $query);
        if ($query != null) {
            $result = $giphy->random($query);
        }

        return $this->response->withJson(array(
            "response_type" => "in_channel",
            "text" => $result->data->image_original_url,
            "username" => "robot",
            "icon_url" => "https://www.mattermost.org/wp-content/uploads/2016/04/icon.png"
        ));
    }
}