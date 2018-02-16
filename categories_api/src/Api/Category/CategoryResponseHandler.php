<?php

namespace Api\Category;

use Propel\Runtime\Collection\ObjectCollection;

class CategoryResponseHandler
{
    /**
     * @param \Category|\Category[] $categoryObject
     * @return array
     */
    public function __invoke($categoryObject): array
    {
        if ($categoryObject instanceof ObjectCollection) {
            return $this->generateResponseCollection($categoryObject);
        }

        return $this->generateResponseSingle($categoryObject);
    }

    /**
     * @param \Category[] $categoryObject
     * @return array
     */
    protected function generateResponseCollection($categoryObject): array
    {
        $returnCollection = [];

        foreach ($categoryObject as $categoryEntity) {
            $returnCollection[] = $this->generateResponseSingle($categoryEntity);
        }

        return $returnCollection;
    }

    /**
     * @param \Category $categoryEntity
     * @return array
     */
    protected function generateResponseSingle(\Category $categoryEntity): array
    {
        return [
            CategorySettingInterface::PARAMETER_UUID => $categoryEntity->getUuid(),
            CategorySettingInterface::PARAMETER_NAME => $categoryEntity->getName(),
            CategorySettingInterface::PARAMETER_SLUG => $categoryEntity->getSlug(),
            CategorySettingInterface::PARAMETER_PARENT_CATEGORY => $categoryEntity->getCategoryRelatedByParentcategoryid() !== null
                ? $categoryEntity->getCategoryRelatedByParentcategoryid()->getUuid()
                : null,
            CategorySettingInterface::PARAMETER_IS_VISIBLE => $categoryEntity->getIsvisible(),
        ];
    }
}
