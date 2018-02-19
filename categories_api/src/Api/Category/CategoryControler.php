<?php

namespace Api\Category;

use Application\BaseController;
use Propel\Runtime\Exception\PropelException;
use Slim\App;
use Slim\Http\Response;

class CategoryControler extends BaseController
{
    /** @var CategoryModel */
    protected $categoryModel;

    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->categoryModel = new CategoryModel();
    }

    /**
     * @return Response
     */
    public function createCategory(): Response
    {
        $requestParameters = $this->getRequest()->getParsedBody();

        $categoryEntity = $this->getCategoryModel()->createCategory($requestParameters);
        if ($categoryEntity === null) {
            return $this->getResponse()->withJson(MessageInterface::ERROR_CATEGORY_EXIST, 409);
        }

        return $this->getResponse()->withJson((new CategoryResponseHandler())($categoryEntity), 201);
    }

    /**
     * @return Response
     */
    public function getCategory(): Response
    {
        $uuidOrSlug = $this->getRequest()->getAttribute(CategorySettingInterface::UUID_OR_SLUG);

        $categoryEntity = $this->getCategoryModel()->getCategoryByUuidOrSlug($uuidOrSlug);
        if ($categoryEntity === null) {
            return $this->getResponse()->withJson(MessageInterface::ERROR_CATEGORY_NOT_FOUND, 404);
        }

        return $this->getResponse()->withJson((new CategoryResponseHandler())($categoryEntity), 200);
    }

    /**
     * @return Response
     * @throws PropelException
     */
    public function getChildrenForCategory(): Response
    {
        $uuidOrSlug = $this->getRequest()->getAttribute(CategorySettingInterface::UUID_OR_SLUG);
        $categoryEntities = $this->getCategoryModel()->getCategoryChildren($uuidOrSlug);

        if ($categoryEntities === null) {
            return $this->getResponse()->withJson(MessageInterface::ERROR_CATEGORY_NOT_FOUND, 404);
        }

        return $this->getResponse()->withJson((new CategoryResponseHandler())($categoryEntities), 200);
    }

    /**
     * @return Response
     * @throws PropelException
     */
    public function updateCategoryPart(): Response
    {
        $uuidOrSlug = $this->getRequest()->getAttribute(CategorySettingInterface::UUID_OR_SLUG);

        $parts = [];
        if (!empty($this->getRequest()->getHeader(CategorySettingInterface::PARAMETER_IS_VISIBLE))) {
            $parts[CategorySettingInterface::PARAMETER_IS_VISIBLE]
                = (bool)$this->getRequest()->getHeader(CategorySettingInterface::PARAMETER_IS_VISIBLE);
        }

        $updateCategory = $this->getCategoryModel()->updateCategoryPart($uuidOrSlug, $parts);

        if ($updateCategory === null) {
            return $this->getResponse()->withJson(MessageInterface::ERROR_CATEGORY_NOT_FOUND, 404);
        }

        return $this->getResponse()->withJson(MessageInterface::SUCCESS_OPERATION, 200);
    }

    /**
     * @return CategoryModel
     */
    protected function getCategoryModel(): CategoryModel
    {
        return $this->categoryModel;
    }
}
