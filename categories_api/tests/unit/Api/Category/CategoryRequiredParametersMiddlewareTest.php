<?php

namespace PhpUnit\Api\Category;

use Api\Category\CategoryRequiredParametersMiddleware;
use Api\Category\CategorySettingInterface;
use Api\Category\MessageInterface;
use Codeception\Test\Unit;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class CategoryRequiredParametersMiddlewareTest extends Unit
{
    public function testInvokeParameterCheckUuid()
    {
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => 'POST',
                'REQUEST_URI' => '/category',
            ]
        );

        $request = Request::createFromEnvironment($environment);
        $request = $request->withParsedBody([CategorySettingInterface::PARAMETER_UUID => 10]);

        $response = new Response();

        $sut = new CategoryRequiredParametersMiddleware([CategorySettingInterface::PARAMETER_NAME]);
        $result = $sut($request, $response, function(){});

        $this->assertEquals(400, $result->getStatusCode());
        $this->assertEquals(['application/json;charset=utf-8'], $result->getHeader('Content-Type'));
        $body = json_decode($result->getBody(), true);
        $this->assertEquals(MessageInterface::ERROR_INVALID_INPUT, $body);
    }
}