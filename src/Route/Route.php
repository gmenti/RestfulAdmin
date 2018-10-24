<?php

namespace RestfulAdmin\Route;

class Route
{
    /** @var string */
    protected $httpMethod;

    /** @var string */
    protected $path;

    /** @var string */
    protected $controllerAction;

    /**
     * Route constructor.
     *
     * @param string $httpMethod
     * @param string $path
     * @param string $controllerAction
     */
    public function __construct($httpMethod, $path, $controllerAction)
    {
        $this->httpMethod = $httpMethod;
        $this->path = $path;
        $this->controllerAction = $controllerAction;
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @param  string $httpMethod
     * @return $this
     */
    public function setHttpMethod($httpMethod)
    {
        $this->httpMethod = $httpMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getControllerAction()
    {
        return $this->controllerAction;
    }

    /**
     * @param string $controllerAction
     * @return $this
     */
    public function setControllerAction($controllerAction)
    {
        $this->controllerAction = $controllerAction;
        return $this;
    }
}