<?php

namespace Api\Category;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class CategoryRequiredParametersMiddleware
{
    /** @var array */
    protected $requiredParameters;

    /**
     * @param array $requiredParameters
     */
    public function __construct(array $requiredParameters)
    {
        $this->setParameters($requiredParameters);
    }

    /**
     * @param array $requiredParameters
     * @return CategoryRequiredParametersMiddleware
     */
    protected function setParameters(array $requiredParameters): CategoryRequiredParametersMiddleware
    {
        $this->requiredParameters = $requiredParameters;

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, Response $response, callable $next): ResponseInterface
    {
        foreach ($this->requiredParameters as $requiredParameter) {
            if (!isset($request->getParsedBody()[$requiredParameter])) {
                return $response->withJson(MessageInterface::ERROR_INVALID_INPUT, 400);
            }
        }

        return $next($request, $response);
    }
}
