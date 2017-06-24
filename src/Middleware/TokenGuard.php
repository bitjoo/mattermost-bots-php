<?php
namespace Bitjo\Middleware;

use Psr\Container\ContainerInterface;
use Slim\Exception\NotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class TokenGuard {
    /**
    * TokenGuard checks if Token of mattermost integration meets the token in settings
    *
    * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
    * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
    * @param  callable                                 $next     Next middleware
    *
    * @return \Psr\Http\Message\ResponseInterface
    */
    private $logger;
    private $tokens;

    public function __construct(ContainerInterface $container) {
        $this->logger = $container['logger']->withName("TokenGuard");
        $this->tokens = $container['tokens'];
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next) {
        $route = $request->getAttribute('route');

        // return NotFound for non existent route
        if (empty($route)) {
            throw new NotFoundException($request, $response);
        }
        $routeName = $route->getName();
        $this->logger->debug("RouteName: " . $routeName);

        $requestToken = $request->getParsedBody()["token"];
        $this->logger->debug("RequestToken: " . $requestToken);

        if (isset($this->tokens[$routeName])) {
            $tokens = $this->tokens[$routeName];
        } else {
            $tokens = null;
        }
        $this->logger->debug("Tokens: " . json_encode($tokens));

        if ($tokens != null && in_array($requestToken, $tokens)) {
            $response = $next($request, $response);
        } else {
            $response = $response->withJson(array(
                "response_type" => "ephemeral",
                "text" => "Your Token `" . $requestToken . "` is invalid."
            ));
        }

        return $response;
    }
}