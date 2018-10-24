<?php

namespace RestfulAdmin\Controller;

use RestfulAdmin\DataProvider;
use RestfulAdmin\Entity\Entity;
use RestfulAdmin\Module\Module;

abstract class Controller
{
    /** @var DataProvider\Contract */
    protected $dataProvider;

    /** @var Module */
    protected $module;

    /** @var Entity */
    protected $entityClass;

    /**
     * Controller constructor.
     *
     * @param DataProvider\Contract $dataProvider
     * @param Module $module
     * @param Entity $entityClass
     */
    public function __construct($dataProvider, $module, $entityClass)
    {
        $this->dataProvider = $dataProvider;
        $this->module = $module;
        $this->entityClass = $entityClass;
    }

    /**
     * @param \Klein\Request $req
     * @return array
     */
    protected function jsonBody($req)
    {
        return json_decode($req->body(), true);
    }

    /**
     * @param array $data
     * @return Entity
     */
    protected function entity($data)
    {
        return new $this->entityClass($data);
    }
}