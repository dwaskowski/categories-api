<?php

namespace Api\Category;

interface MessageInterface
{
    const SUCCESS_OPERATION = 'Successful operation';
    const SUCCESS_CATEGORY_CREATED = 'Category created';

    const ERROR_INVALID_ID = 'Invalid uuid or slug supplied';
    const ERROR_CATEGORY_NOT_FOUND = 'Category not found';
    const ERROR_INVALID_INPUT = 'Invalid input';
    const ERROR_CATEGORY_EXIST = 'Category already exists';
}