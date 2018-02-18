<?php

namespace Integration\Api\Category;

use Api\Category\CategorySettingInterface;
use Api\Category\MessageInterface;

class CategoryControlerCest
{
    public function _before(\IntegrationTester $I)
    {
    }

    public function _after(\IntegrationTester $I)
    {
    }

    public function createCategory(\IntegrationTester $I)
    {
        $I->sendPOST('/category', [
            CategorySettingInterface::PARAMETER_NAME => 'TestNameTwo',
            CategorySettingInterface::PARAMETER_SLUG => 'tnt'
        ]);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(201);
        $I->canSeeInDatabase('category', ['name' => 'TestNameTwo', 'slug' => 'tnt']);

        $I->sendPOST('/category', [
            CategorySettingInterface::PARAMETER_NAME => 'TestNameTwo',
            CategorySettingInterface::PARAMETER_SLUG => 'tnt'
        ]);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(409);
        $I->canSeeResponseContains(MessageInterface::ERROR_CATEGORY_EXIST);

        $I->sendPOST('/category', [
            CategorySettingInterface::PARAMETER_NAME => 'TestNameTwo'
        ]);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->canSeeResponseContains(MessageInterface::ERROR_INVALID_INPUT);
    }

    public function getCategory(\IntegrationTester $I)
    {
        $I->sendGET('/category/tno', []);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseJsonMatchesXpath('uuid');
        $I->canSeeResponseJsonMatchesXpath('name');
        $I->canSeeResponseJsonMatchesXpath('slug');
        $I->canSeeResponseJsonMatchesXpath('parentCategory');
        $I->canSeeResponseJsonMatchesXpath('isVisible');

        $I->sendGET('/category/tnf', []);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseContains(MessageInterface::ERROR_CATEGORY_NOT_FOUND);

        $I->sendGET('/category/20', []);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->canSeeResponseContains(MessageInterface::ERROR_INVALID_ID);
    }

    public function getChildrenForCategory(\IntegrationTester $I)
    {
        $I->sendGET('/category/tno/tree', []);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(200);

        $I->sendGET('/category/tnf/tree', []);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseContains(MessageInterface::ERROR_CATEGORY_NOT_FOUND);

        $I->sendGET('/category/20/tree', []);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->canSeeResponseContains(MessageInterface::ERROR_INVALID_ID);
    }

    public function updateCategoryPart(\IntegrationTester $I)
    {
        $I->sendPATCH('/category/tno', [
            CategorySettingInterface::PARAMETER_IS_VISIBLE => true
        ]);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContains(MessageInterface::SUCCESS_OPERATION);

        $I->sendPATCH('/category/tnf', []);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseContains(MessageInterface::ERROR_CATEGORY_NOT_FOUND);

        $I->sendPATCH('/category/20', []);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->canSeeResponseContains(MessageInterface::ERROR_INVALID_ID);
    }
}
