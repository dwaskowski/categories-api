<?php

namespace  PhpUnit\Api\Category;

use Api\Category\CategoryControler;
use Api\Category\CategoryModel;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Propel\Runtime\Collection\ObjectCollection;
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

    /**
     * @dataProvider getGetChildrenForCategoryData
     */
    public function testGetChildrenForCategory($categoryEntities, $statusCode)
    {
        $baseControler = Stub::make(CategoryControler::class, ['getCategoryModel' => function () use ($categoryEntities) {
            return  Stub::make(CategoryModel::class, ['getCategoryChildren' => $categoryEntities]);
        }]);

        $baseControler->setResponse(new Response());
        $baseControler->setRequest(Stub::make(Request::class, ['getAttribute' => '']));

        /** @var Response $result */
        $result = $baseControler->getChildrenForCategory();

        $this->assertEquals($statusCode, $result->getStatusCode());
    }

    public function getGetChildrenForCategoryData()
    {
        return [
            [new ObjectCollection(), 200],
            [null, 404]
        ];
    }

    /**
     * @dataProvider getUpdateCategoryPartData
     */
    public function testUpdateCategoryPart($updateCategoryPart, $headerParam, $statusCode)
    {
        $baseControler = Stub::make(CategoryControler::class, ['getCategoryModel' => function () use ($updateCategoryPart) {
            return  Stub::make(CategoryModel::class, ['updateCategoryPart' => $updateCategoryPart]);
        }]);

        $baseControler->setResponse(new Response());
        $baseControler->setRequest(Stub::make(Request::class, [
            'getAttribute' => '',
            'getHeader' => $headerParam
        ]));

        /** @var Response $result */
        $result = $baseControler->updateCategoryPart();

        $this->assertEquals($statusCode, $result->getStatusCode());
    }

    public function getUpdateCategoryPartData()
    {
        return [
            [true, '', 200],
            [false, '', 200],
            [null, '', 404],
            [true, true, 200],
            [false, true, 200],
            [null, false, 404]
        ];
    }
}
