<?php

namespace  PhpUnit\Api\Category;

use Api\Category\CategoryControler;
use Api\Category\CategoryModel;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Slim\Http\Request;
use Slim\Http\Response;

class CategoryControllerTest extends Unit
{
    public function testCreateCategory()
    {
        $baseControler = Stub::make(CategoryControler::class, ['getCategoryModel' => function () {
            return Stub::make(
                CategoryModel::class,
                [
                    'createCategory' => new \Category()
                ]
            );
        }]);

        $baseControler->setResponse(new Response());
        $baseControler->setRequest(Stub::make(Request::class, ['getParsedBody' => []]));

        /** @var Response $result */
        $result = $baseControler->createCategory();

        $this->assertEquals(201, $result->getStatusCode());

        $baseControler = Stub::make(CategoryControler::class, ['getCategoryModel' => function () {
            return Stub::make(
                CategoryModel::class,
                [
                    'createCategory' => null
                ]
            );
        }]);

        $baseControler->setResponse(new Response());
        $baseControler->setRequest(Stub::make(Request::class, ['getParsedBody' => []]));

        /** @var Response $result */
        $result = $baseControler->createCategory();

        $this->assertEquals(409, $result->getStatusCode());
    }
}
