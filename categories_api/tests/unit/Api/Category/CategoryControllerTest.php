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
    /**
     * @dataProvider getCreateCategoryData
     */
    public function testCreateCategory($categoryEntity, $statusCode)
    {
        $baseControler = Stub::make(CategoryControler::class, ['getCategoryModel' => function () use ($categoryEntity) {
            return  Stub::make(CategoryModel::class, ['createCategory' => $categoryEntity]);
        }]);

        $baseControler->setResponse(new Response());
        $baseControler->setRequest(Stub::make(Request::class, ['getParsedBody' => []]));

        /** @var Response $result */
        $result = $baseControler->createCategory();

        $this->assertEquals($statusCode, $result->getStatusCode());
    }

    public function getCreateCategoryData()
    {
        return [
            [new \Category(), 201],
            [null, 409]
        ];
    }

    /**
     * @dataProvider getGetCategoryData
     */
    public function testGetCategory($categoryEntity, $statusCode)
    {
        $baseControler = Stub::make(CategoryControler::class, ['getCategoryModel' => function () use ($categoryEntity) {
            return  Stub::make(CategoryModel::class, ['getCategoryByUuidOrSlug' => $categoryEntity]);
        }]);

        $baseControler->setResponse(new Response());
        $baseControler->setRequest(Stub::make(Request::class, ['getAttribute' => '']));

        /** @var Response $result */
        $result = $baseControler->getCategory();

        $this->assertEquals($statusCode, $result->getStatusCode());
    }

    public function getGetCategoryData()
    {
        return [
            [new \Category(), 200],
            [null, 404]
        ];
    }
}
