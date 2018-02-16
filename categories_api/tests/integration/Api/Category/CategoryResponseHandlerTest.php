<?php

namespace  Integration\Api\Category;

use Api\Category\CategoryResponseHandler;
use Api\Category\CategorySettingInterface;
use Codeception\Test\Unit;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryResponseHandlerTest extends Unit
{
    public function testInvokeForSingleResponse()
    {
        $categoryEntity = new \Category();
        $categoryEntity->setUuid('uuid')
            ->setName('TestName')
            ->setSlug('slug')
            ->setIsvisible(true);

        $categoryResponseHandler = new CategoryResponseHandler();
        $result = $categoryResponseHandler($categoryEntity);

        $this->assertInternalType('array', $result);

        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_UUID, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_NAME, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_SLUG, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_PARENT_CATEGORY, $result);
        $this->assertArrayHasKey(CategorySettingInterface::PARAMETER_IS_VISIBLE, $result);
    }

    public function testInvokeForCollectionResponse()
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

        $categoryResponseHandler = new CategoryResponseHandler();
        $resultArray = $categoryResponseHandler($objectCollection);
        $this->assertInternalType('array', $resultArray);
        $this->assertEquals(2, count($resultArray));

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

        $categoryResponseHandler = new CategoryResponseHandler();
        $resultArray = $this->getCategoryReflectionMethod('generateResponseCollection')
            ->invokeArgs($categoryResponseHandler, [[$categoryEntity, $secondCategoryEntity]]);

        $this->assertInternalType('array', $resultArray);

        foreach ($resultArray as $result) {
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
        }
    }

    protected function getCategoryReflectionMethod(string $methodName)
    {
        $class = new \ReflectionClass(CategoryResponseHandler::class);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
