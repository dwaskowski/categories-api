<?php

namespace  PhpUnit\Api\Category;

use Api\Category\CategoryResponseHandler;
use Api\Category\CategorySettingInterface;
use Codeception\Test\Unit;

class CategoryResponseHandlerTest extends Unit
{
    public function testGenerateResponseSingle()
    {
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
    }

    protected function getCategoryReflectionMethod(string $methodName)
    {
        $class = new \ReflectionClass(CategoryResponseHandler::class);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
