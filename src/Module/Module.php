<?php

namespace RestfulAdmin\Module;

use RestfulAdmin\Route\Route;
use RestfulAdmin\Entity\Entity;

class Module
{
    /** @var string */
    protected $name;

    /** @var Route[] */
    protected $routes;

    /** @var string */
    protected $entityClass;

    /** @var string */
    protected $controllerClass;

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param  Route[] $routes
     * @return $this
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    /**
     * @param string $controllerClass
     * @return $this
     */
    public function setControllerClass($controllerClass)
    {
        $this->controllerClass = $controllerClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param string $entityClass
     * @return $this
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }
}