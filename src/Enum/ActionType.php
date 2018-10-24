<?php

namespace RestfulAdmin\Enum;

class ActionType extends \MyCLabs\Enum\Enum
{
    const GET_ONE = 'get_one';
    const GET_MANY = 'get_many';
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';
}