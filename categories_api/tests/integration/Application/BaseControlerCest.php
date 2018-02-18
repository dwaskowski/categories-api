<?php

namespace Integration\Application;

class BaseControlerCest
{
    public function _before(\IntegrationTester $I)
    {
    }

    public function _after(\IntegrationTester $I)
    {
    }

    public function checkHomePage(\IntegrationTester $I)
    {
        $I->sendGET('', []);

        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(200);
    }
}