<?php

namespace RestfulAdmin\DataProvider;

use RestfulAdmin\Enum\ActionType;

interface Contract
{
    /**
     * @param string $resource
     * @param string $type
     * @param array $params
     * @return array
     */
    public function execute($resource, $type, $params);
}
