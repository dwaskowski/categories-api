<?php

namespace PhpUnit\Api\Category;

use Api\Category\CategoryModel;
use Api\Category\CategorySettingInterface;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Propel\Runtime\Exception\PropelException;

class CategoryModelTest extends Unit
{
    public function testCreateCategoryIsNew()
    {
        $categoryModelMock = Stub::make(
            CategoryModel::class,
            [
                'findOrCreateCategoryByName' => function () {
                    $categoryEntity = Stub::make(\Category::class, ['save' => true]);
                    $categoryEntity->setName('TestCategory');

                    return $categoryEntity;
                }
            ]
        );

        /** @var \Category $result */
        $result = $categoryModelMock->createCategory([
            CategorySettingInterface::PARAMETER_UUID => 'uuid',
            CategorySettingInterface::PARAMETER_NAME => 'TestCategory',
            CategorySettingInterface::PARAMETER_SLUG => 'tc',
            CategorySettingInterface::PARAMETER_IS_VISIBLE => true
        ]);

        $this->assertInstanceOf(\Category::class, $result);
        $this->assertEquals($result->getUuid(), 'uuid');
        $this->assertEquals($result->getName(), 'TestCategory');
        $this->assertEquals($result->getSlug(), 'tc');
        $this->assertEquals($result->getIsvisible(), true);
    }

    public function testCreateCategoryIsNotNew()
    {
        $categoryModelMock = Stub::make(
            CategoryModel::class,
            [
                'findOrCreateCategoryByName' => function () {
                    $categoryEntity = Stub::make(\Category::class, ['save' => true, 'isNew' => false]);

                    return $categoryEntity;
                }
            ]
        );

        /** @var \Category $result */
        $result = $categoryModelMock->createCategory([
            CategorySettingInterface::PARAMETER_NAME => 'TestCategory',
            CategorySettingInterface::PARAMETER_SLUG => 'tc'
        ]);

        $this->assertEquals($result, null);
    }

    public function testCreateCategorySaveExeption()
    {
        $categoryModelMock = Stub::make(
            CategoryModel::class,
            [
                'findOrCreateCategoryByName' => function () {
                    $categoryEntity = Stub::make(\Category::class, ['save' => function () {
                        throw new PropelException('TestExeption');
                    }]);

                    return $categoryEntity;
                }
            ]
        );

        /** @var \Category $result */
        $result = $categoryModelMock->createCategory([
            CategorySettingInterface::PARAMETER_NAME => 'TestCategory',
            CategorySettingInterface::PARAMETER_SLUG => 'tc'
        ]);

        $this->assertEquals($result, null);
    }

    public function testGetForNotExistCategoryChildren()
    {
        $categoryModelMock = Stub::make(CategoryModel::class, ['getCategoryByUuidOrSlug' => null]);

        $result = $categoryModelMock->getCategoryChildren('uuid');

        $this->assertEquals($result, null);
    }

    public function testUpdateCategoryIsModifiedPart()
    {
        $categoryModelMock = Stub::make(
            CategoryModel::class,
            [
                'getCategoryByUuidOrSlug' => function () {
                    $categoryEntityMock = Stub::make(\Category::class, ['save' => true]);

                    return $categoryEntityMock;
                }
            ]
        );

        /** @var \Category $result */
        $result = $categoryModelMock->updateCategoryPart(
            'uuid',
            [CategorySettingInterface::PARAMETER_IS_VISIBLE => true]
        );

        $this->assertEquals(true, $result);
    }

    public function testUpdateCategoryIsNotModifiedPart()
    {
        $categoryModelMock = Stub::make(
            CategoryModel::class,
            [
                'getCategoryByUuidOrSlug' => function () {
                    $categoryEntityMock = Stub::make(\Category::class, ['save' => true]);

                    return $categoryEntityMock;
                }
            ]
        );

        /** @var \Category $result */
        $result = $categoryModelMock->updateCategoryPart(
            'uuid',
            [CategorySettingInterface::PARAMETER_NAME => 'test2']
        );

        $this->assertEquals(false, $result);
    }

    public function testUpdateForNotExistsCategoryPart()
    {
        $categoryModelMock = Stub::make(CategoryModel::class, ['getCategoryByUuidOrSlug' => null]);

        $result = $categoryModelMock->getCategoryChildren('uuid');

        $this->assertEquals($result, null);
    }

    public function testGetCategoryQuery()
    {
        $categoryModel = new CategoryModel();
        $class = new \ReflectionClass(CategoryModel::class);
        $method = $class->getMethod('getCategoryQuery');
        $method->setAccessible(true);
        $result = $method->invokeArgs($categoryModel, []);

        $this->assertInstanceOf(\CategoryQuery::class, $result);
    }

    public function testFindOrCreateCategoryByName()
    {
        $categoryModel = $this->getMockBuilder(CategoryModel::class)->setMethods(['getCategoryQuery'])->getMock();
        $categoryModel->method('getCategoryQuery')->willReturnCallback(function () {
            $categoryEntity = new \Category();
            $categoryQuery = Stub::make(\CategoryQuery::class, ['findOneOrCreate' => $categoryEntity]);

            return $categoryQuery;
        });

        $class = new \ReflectionClass(CategoryModel::class);
        $method = $class->getMethod('findOrCreateCategoryByName');
        $method->setAccessible(true);
        $result = $method->invokeArgs($categoryModel, ['test']);

        $this->assertInstanceOf(\Category::class, $result);
    }
}