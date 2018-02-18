<?php

namespace  Api\Category;

use Application\UuidHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Slim\Route;

class CategoryCheckUuidMiddleware
{
    /** @var array */
    protected $requiredAttributes;

    /** @var array */
    protected $requiredParameters;

    /**
     * @param array $requiredAttributes
     * @param array $requiredParameters
     */
    public function __construct(array $requiredAttributes = [], array $requiredParameters = [])
    {
        $this->setAttributes($requiredAttributes)
            ->setParameters($requiredParameters);
    }

    /**
     * @param array $requiredAttributes
     * @return CategoryCheckUuidMiddleware
     */
    protected function setAttributes(array $requiredAttributes = []): CategoryCheckUuidMiddleware
    {
        $this->requiredAttributes = $requiredAttributes;

        return $this;
    }

    /**
     * @param array $requiredParameters
     * @return CategoryCheckUuidMiddleware
     */
    protected function setParameters(array $requiredParameters = []): CategoryCheckUuidMiddleware
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
        foreach ($this->requiredAttributes as $requiredAttribute) {
            /** @var Route $route */
            $route = $request->getAttribute('route');
            $uuidOrSlug = $route->getArgument($requiredAttribute);

            if (!UuidHelper::isV4($uuidOrSlug) && !(bool)preg_match('/([a-z_-]+)/', $uuidOrSlug)) {
                return $response->withJson(MessageInterface::ERROR_INVALID_ID, 400);
            }
        }

        foreach ($this->requiredParameters as $requiredParameter) {
            $parameter = $request->getParsedBody()[$requiredParameter] ?? null;
            if ($parameter !== null && !UuidHelper::isV4($parameter)) {
                return $response->withJson(MessageInterface::ERROR_INVALID_INPUT, 400);
            }
        }

        return $next($request, $response);
    }
}
