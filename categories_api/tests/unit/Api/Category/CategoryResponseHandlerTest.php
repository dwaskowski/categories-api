<?php

namespace  PhpUnit\Api\Category;

use Api\Category\CategoryResponseHandler;
use Api\Category\CategorySettingInterface;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryResponseHandlerTest extends Unit
{
    public function testInvoke()
    {
        $categoryEntity = new \Category();
        $categoryEntity->setUuid('uuid')
            ->setName('TestName')
            ->setSlug('slug')
            ->setIsvisible(true);

        $categoryHandler = Stub::make(CategoryResponseHandler::class, ['generateResponseSingle' => []]);

        $result = $categoryHandler($categoryEntity);

        $this->assertInternalType('array', $result);

        $secondCategoryEntity = new \Category();
        $secondCategoryEntity->setUuid('uuid')
            ->setName('TestName')
            ->setSlug('slug')
            ->setIsvisible(false);

        $objectCollection = new ObjectCollection();
        $objectCollection->append($categoryEntity);
        $objectCollection->append($secondCategoryEntity);

        $categoryHandler = Stub::make(CategoryResponseHandler::class, ['generateResponseCollection' => [[], []]]);
        $resultArray = $categoryHandler($objectCollection);

        $this->assertInternalType('array', $resultArray);
        $this->assertEquals(2, count($resultArray));
    }

    public function testGenerateResponseSingle()
    {
        $categoryParentEntity = new \Category();
        $categoryParentEntity->setUuid('uuid2');

        $categoryEntity = new \Category();
        $categoryEntity->setUuid('uuid')
            ->setName('TestName')
            ->setSlug('slug')
            ->setIsvisible(true);

        $categoryHandler = new CategoryResponseHandler();
        $result = $this->getCategoryReflectionMethod('generateResponseSingle')
            ->invokeArgs($categoryHandler, [$categoryEntity]);

        $this->assertInternalType('array', $result);

        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_UUID, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_NAME, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_SLUG, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_PARENT_CATEGORY, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_IS_VISIBLE, $result);

        $this->assertInternalType('string', $result[CategorySettingInterface::PARAMETER_UUID]);
        $this->assertInternalType('string', $result[CategorySettingInterface::PARAMETER_NAME]);
        $this->assertInternalType('string', $result[CategorySettingInterface::PARAMETER_SLUG]);
        $this->assertInternalType('bool', $result[CategorySettingInterface::PARAMETER_IS_VISIBLE]);

        $this->assertEquals('uuid', $result[CategorySettingInterface::PARAMETER_UUID]);
        $this->assertEquals('TestName', $result[CategorySettingInterface::PARAMETER_NAME]);
        $this->assertEquals('slug', $result[CategorySettingInterface::PARAMETER_SLUG]);
        $this->assertEquals(null, $result[CategorySettingInterface::PARAMETER_PARENT_CATEGORY]);
        $this->assertEquals(true, $result[CategorySettingInterface::PARAMETER_IS_VISIBLE]);

        $categoryEntity->setCategoryRelatedByParentcategoryid($categoryParentEntity);

        $result = $this->getCategoryReflectionMethod('generateResponseSingle')
            ->invokeArgs($categoryHandler, [$categoryEntity]);

        $this->assertInternalType('array', $result);

        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_UUID, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_NAME, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_SLUG, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_PARENT_CATEGORY, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_IS_VISIBLE, $result);

        $this->assertInternalType('string', $result[CategorySettingInterface::PARAMETER_UUID]);
        $this->assertInternalType('string', $result[CategorySettingInterface::PARAMETER_NAME]);
        $this->assertInternalType('string', $result[CategorySettingInterface::PARAMETER_SLUG]);
        $this->assertInternalType('bool', $result[CategorySettingInterface::PARAMETER_IS_VISIBLE]);
        $this->assertInternalType('string', $result[CategorySettingInterface::PARAMETER_PARENT_CATEGORY]);


        $this->assertEquals('uuid', $result[CategorySettingInterface::PARAMETER_UUID]);
        $this->assertEquals('TestName', $result[CategorySettingInterface::PARAMETER_NAME]);
        $this->assertEquals('slug', $result[CategorySettingInterface::PARAMETER_SLUG]);
        $this->assertEquals('uuid2', $result[CategorySettingInterface::PARAMETER_PARENT_CATEGORY]);
        $this->assertEquals(true, $result[CategorySettingInterface::PARAMETER_IS_VISIBLE]);
    }

    public function testGenerateResponseCollection()
    {
        $categoryEntity = new \Category();
        $categoryEntity->setUuid('uuid')
            ->setName('TestName')
            ->setSlug('slug')
            ->setIsvisible(true);

        $secondCategoryEntity = new \Category();
        $secondCategoryEntity->setUuid('uuid')
            ->setName('TestName')
            ->setSlug('slug')
            ->setIsvisible(false);

        $objectCollection = new ObjectCollection();
        $objectCollection->append($categoryEntity);
        $objectCollection->append($secondCategoryEntity);

        $categoryHandler = Stub::make(CategoryResponseHandler::class, ['generateResponseSingle' => []]);

        $result = $this->getCategoryReflectionMethod('generateResponseCollection')
            ->invokeArgs($categoryHandler, [$objectCollection]);

        $this->assertInternalType('array', $result);
        $this->assertEquals(2, count($result));

        $objectCollection = new ObjectCollection();

        $result = $this->getCategoryReflectionMethod('generateResponseCollection')
            ->invokeArgs($categoryHandler, [$objectCollection]);

        $this->assertInternalType('array', $result);
    }

    protected function getCategoryReflectionMethod(string $methodName)
    {
        $class = new \ReflectionClass(CategoryResponseHandler::class);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
