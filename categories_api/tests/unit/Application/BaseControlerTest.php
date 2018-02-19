<?php

namespace  PhpUnit\Application;

use Application\BaseController;
use Codeception\Test\Unit;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class BaseControlerTest extends Unit
{
    public function testIndex()
    {
        $baseControler = new BaseController(new App());
        $baseControler->setResponse(new Response());

        $result = $baseControler->index();

        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testGetRequest()
    {
        $class = new \ReflectionClass(BaseController::class);
        $method = $class->getMethod('getRequest');
        $method->setAccessible(true);

        $baseControler = new BaseController(new App());
        $baseControler->setRequest($this->createMock(Request::class));

        $this->assertInstanceOf(Request::class, $method->invokeArgs($baseControler, []));
    }
}
