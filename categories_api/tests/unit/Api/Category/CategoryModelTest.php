<?php

namespace PhpUnit\Api\Category;

use Api\Category\CategoryModel;
use Api\Category\CategorySettingInterface;
use Codeception\Test\Unit;

class CategoryModelTest extends Unit
{
    public function testCreateCategoryIsNew()
    {
        $categoryModelMock = $this->getMockBuilder(CategoryModel::class)
            ->setMethods(['findOrCreateCategoryByName'])
            ->getMock();
        $categoryModelMock->method('findOrCreateCategoryByName')
            ->willReturnCallback(function () {
                $categoryEntityMock = $this->createMock(\Category::class);
                $categoryEntityMock->method('save')->willReturn(true);
                $categoryEntityMock->method('isNew')->willReturn(true);

                $categoryEntityMock->method('getUuid')->willReturn('uuid');
                $categoryEntityMock->method('getName')->willReturn('TestCategory');
                $categoryEntityMock->method('getSlug')->willReturn('tc');
                $categoryEntityMock->method('getIsvisible')->willReturn(true);

                return $categoryEntityMock;
            });

        /** @var \Category $result */
        $result = $categoryModelMock->createCategory([
            CategorySettingInterface::PARAMETER_NAME => 'TestCategory',
            CategorySettingInterface::PARAMETER_SLUG => 'tc'
        ]);

        $this->assertInstanceOf(\Category::class, $result);
        $this->assertEquals($result->getUuid(), 'uuid');
        $this->assertEquals($result->getName(), 'TestCategory');
        $this->assertEquals($result->getSlug(), 'tc');
        $this->assertEquals($result->getIsvisible(), true);
    }

    public function testCreateCategoryIsNotNew()
    {
        $categoryModelMock = $this->getMockBuilder(CategoryModel::class)
            ->setMethods(['findOrCreateCategoryByName'])
            ->getMock();
        $categoryModelMock->method('findOrCreateCategoryByName')
            ->willReturnCallback(function () {
                $categoryEntityMock = $this->createMock(\Category::class);
                $categoryEntityMock->method('save')->willReturn(true);
                $categoryEntityMock->method('isNew')->willReturn(false);

                return $categoryEntityMock;
            });

        /** @var \Category $result */
        $result = $categoryModelMock->createCategory([
            CategorySettingInterface::PARAMETER_NAME => 'TestCategory',
            CategorySettingInterface::PARAMETER_SLUG => 'tc'
        ]);

        $this->assertEquals($result, null);
    }

    public function testGetForNotExistCategoryChildren()
    {
        $categoryModelMock = $this->getMockBuilder(CategoryModel::class)
            ->setMethods(['getCategoryByUuidOrSlug'])
            ->getMock();

        $categoryModelMock->method('getCategoryByUuidOrSlug')->willReturn(null);

        $result = $categoryModelMock->getCategoryChildren('uuid');

        $this->assertEquals($result, null);
    }

    public function testUpdateCategoryIsModifiedPart()
    {
        $categoryModelMock = $this->getMockBuilder(CategoryModel::class)
            ->setMethods(['getCategoryByUuidOrSlug'])
            ->getMock();
        $categoryModelMock->method('getCategoryByUuidOrSlug')
            ->willReturnCallback(function () {
                $categoryEntityMock = $this->createMock(\Category::class);
                $categoryEntityMock->expects($this->any())->method('save')->willReturn(true);
                $categoryEntityMock->expects($this->any())->method('isModified')->willReturnSelf();
                $categoryEntityMock->expects($this->any())->method('setIsvisible')->willReturnSelf();

                return $categoryEntityMock;
            });

        /** @var \Category $result */
        $result = $categoryModelMock->updateCategoryPart(
            'uuid',
            [CategorySettingInterface::PARAMETER_IS_VISIBLE => true]
        );

        $this->assertEquals(true, $result);
    }

    public function testUpdateCategoryIsNotModifiedPart()
    {
        $categoryModelMock = $this->getMockBuilder(CategoryModel::class)
            ->setMethods(['getCategoryByUuidOrSlug'])
            ->getMock();
        $categoryModelMock->method('getCategoryByUuidOrSlug')
            ->willReturn(new \Category());

        /** @var \Category $result */
        $result = $categoryModelMock->updateCategoryPart(
            'uuid',
            []
        );

        $this->assertEquals(false, $result);
    }

    public function testUpdateForNotExistsCategoryPart()
    {
        $categoryModelMock = $this->getMockBuilder(CategoryModel::class)
            ->setMethods(['getCategoryByUuidOrSlug'])
            ->getMock();

        $categoryModelMock->method('getCategoryByUuidOrSlug')->willReturn(null);

        $result = $categoryModelMock->getCategoryChildren('uuid');

        $this->assertEquals($result, null);
    }
}