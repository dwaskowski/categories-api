<?php

use Api\Category\CategoryControler;
use Application\BaseController;
use Api\Category\CategorySettingInterface;
use Api\Category\CategoryRequiredParametersMiddleware;
use Api\Category\CategoryCheckUuidMiddleware;

// Categories API
$categoryController = new CategoryControler($app);
$app->post('/category', $categoryController('createCategory'))
    ->add(new CategoryRequiredParametersMiddleware([
        CategorySettingInterface::PARAMETER_NAME,
        CategorySettingInterface::PARAMETER_SLUG
    ]))
    ->add(new CategoryCheckUuidMiddleware([], [CategorySettingInterface::PARAMETER_UUID, CategorySettingInterface::PARAMETER_PARENT_CATEGORY]));

$app->get('/category/{' . CategorySettingInterface::UUID_OR_SLUG . '}', $categoryController('getCategory'))
    ->add(new CategoryCheckUuidMiddleware([CategorySettingInterface::UUID_OR_SLUG]));

$app->get('/category/{' . CategorySettingInterface::UUID_OR_SLUG . '}/tree', $categoryController('getChildrenForCategory'))
    ->add(new CategoryCheckUuidMiddleware([CategorySettingInterface::UUID_OR_SLUG]));

$app->patch('/category/{' . CategorySettingInterface::UUID_OR_SLUG . '}', $categoryController('updateCategoryPart'))
    ->add(new CategoryCheckUuidMiddleware([CategorySettingInterface::UUID_OR_SLUG]));

// Default
$app->get('/[{name}]',(new BaseController($app))('index'));
