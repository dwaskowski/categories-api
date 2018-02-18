<?php

namespace  PhpUnit\Api\Category;

use Api\Category\CategoryCheckUuidMiddleware;
use Api\Category\CategorySettingInterface;
use Api\Category\MessageInterface;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Route;

class CategoryCheckUuidMiddlewareTest extends Unit
{
    public function testInvokeAttributeCheckUuid()
    {
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/category/10',
            ]
        );

        $routeMock = $this->createMock(Route::class);
        $routeMock->method('getArgument')->willReturn('10');

        $request = Request::createFromEnvironment($environment);
        $request = $request->withAttribute('route', $routeMock);

        $response = new Response();

        $sut = new CategoryCheckUuidMiddleware([CategorySettingInterface::UUID_OR_SLUG]);
        $result = $sut($request, $response, function(){});

        $this->assertEquals(400, $result->getStatusCode());
        $this->assertEquals(['application/json;charset=utf-8'], $result->getHeader('Content-Type'));
        $body = json_decode($result->getBody(), true);
        $this->assertEquals(MessageInterface::ERROR_INVALID_ID, $body);
    }

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

        $sut = new CategoryCheckUuidMiddleware([], [CategorySettingInterface::PARAMETER_UUID]);
        $result = $sut($request, $response, function(){});

        $this->assertEquals(400, $result->getStatusCode());
        $this->assertEquals(['application/json;charset=utf-8'], $result->getHeader('Content-Type'));
        $body = json_decode($result->getBody(), true);
        $this->assertEquals(MessageInterface::ERROR_INVALID_INPUT, $body);
    }
}
