<?php

namespace Api\Category;

use Application\UuidHelper;
use Propel\Runtime\Exception\PropelException;

class CategoryModel
{
    /**
     * @return \CategoryQuery
     */
    protected function getCategoryQuery(): \CategoryQuery
    {
        return \CategoryQuery::create();
    }

    /**
     * @param string $uuidOrSlug
     * @return \Category|null
     */
    public function getCategoryByUuidOrSlug(string $uuidOrSlug)
    {
        return $this->getCategoryQuery()
            ->filterByUuid($uuidOrSlug)
            ->_or()
            ->filterBySlug($uuidOrSlug)
            ->findOne();
    }

    /**
     * @param array $requestParameters
     * @return \Category|null
     * @throws PropelException
     */
    public function createCategory(array $requestParameters)
    {
        $uuid = $requestParameters[CategorySettingInterface::PARAMETER_UUID] ?? UuidHelper::v4();
        $parentCategory = isset($requestParameters[CategorySettingInterface::PARAMETER_PARENT_CATEGORY])
            ? $this->getCategoryByUuidOrSlug($requestParameters[CategorySettingInterface::PARAMETER_PARENT_CATEGORY])
            : null;
        $isVisible = $requestParameters[CategorySettingInterface::PARAMETER_IS_VISIBLE] ?? false;

        $categoryEntity = $this->getCategoryQuery()
            ->filterByName($requestParameters[CategorySettingInterface::PARAMETER_NAME])
            ->findOneOrCreate();

        if (!$categoryEntity->isNew()) {
            return null;
        }

        $categoryEntity->setSlug($requestParameters[CategorySettingInterface::PARAMETER_SLUG]);
        $categoryEntity->setUuid($uuid);
        $categoryEntity->setCategoryRelatedByParentcategoryid($parentCategory);
        $categoryEntity->setIsvisible($isVisible);

        try {
            $categoryEntity->save();
        } catch (\Exception $error) {
            return null;
        }

        return $categoryEntity;
    }

    /**
     * @param string $uuidOrSlug
     * @return \Category[]|null|\Propel\Runtime\Collection\ObjectCollection
     * @throws PropelException
     */
    public function getCategoryChildren(string $uuidOrSlug)
    {
        $parentCategoryEntity = $this->getCategoryByUuidOrSlug($uuidOrSlug);

        if ($parentCategoryEntity === null) {
            return null;
        }

        return $this->getCategoryQuery()
            ->filterByCategoryRelatedByParentcategoryid($parentCategoryEntity)
            ->find();
    }

    /**
     * @param string $uuidOrSlug
     * @param array $categoryParts
     * @return bool|null
     * @throws PropelException
     */
    public function updateCategoryPart(string $uuidOrSlug, array $categoryParts)
    {
        $categoryEntity = $this->getCategoryByUuidOrSlug($uuidOrSlug);

        if ($categoryEntity === null) {
            return null;
        }

        foreach ($categoryParts as $categoryPart => $value) {
            switch ($categoryPart) {
                case CategorySettingInterface::PARAMETER_IS_VISIBLE:
                    $categoryEntity->setIsvisible($value);

                    break;
            }
        }

        if (!$categoryEntity->isModified()) {
            return false;
        }

        $categoryEntity->save();

        return true;
    }
}