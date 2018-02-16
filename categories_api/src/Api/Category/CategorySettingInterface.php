<?php

namespace Api\Category;

interface CategorySettingInterface
{
    const UUID_OR_SLUG = 'uuidOrSlug';

    const PARAMETER_UUID = 'uuid';
    const PARAMETER_NAME = 'name';
    const PARAMETER_SLUG = 'slug';
    const PARAMETER_PARENT_CATEGORY = 'parentCategory';
    const PARAMETER_IS_VISIBLE = 'isVisible';
}