<?php

namespace RestfulAdmin;

use RestfulAdmin\DataProvider;
use RestfulAdmin\Exception\NotFoundException;
use RestfulAdmin\Exception\ValidationException;
use RestfulAdmin\Module\Module;

class App
{
    /** @var Module[] */
    protected $modules;

    /** @var DataProvider\Contract */
    protected $dataProvider;

    /**
     * @return Module[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param  Module[] $modules
     * @return $this
     */
    public function setModules($modules)
    {
        $this->modules = $modules;
        return $this;
    }

    /**
     * @return DataProvider\Contract
     */
    public function getDataProvider()
    {
        return $this->dataProvider;
    }

    /**
     * @param  DataProvider\Contract $dataProvider
     * @return $this
     */
    public function setDataProvider($dataProvider)
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    /**
     * Start application.
     *
     * @return void
     *
     * @throws
     */
    public function start()
    {
        $httpServer = new \Klein\Klein;
        $dataProvider = $this->dataProvider;

        header('Accept: application/json');
        header('Content-Type: application/json');
        date_default_timezone_set("UTC");

        foreach ($this->modules as $module) {

            foreach ($module->getRoutes() as $route) {
                $fullPath = '/' . strtolower($module->getName()) . $route->getPath();

                $httpServer->respond($route->getHttpMethod(), $fullPath,
                    /**
                     * @param \Klein\Request $req
                     * @param \Klein\Response $res
                     * @return mixed
                     */
                    function ($req, $res) use ($module, $route, $dataProvider) {
                        $controllerClass = $module->getControllerClass();
                        $controllerAction = $route->getControllerAction();
                        $entityClass = $module->getEntityClass();

                        try {
                            $result = (new $controllerClass($dataProvider, $module, $entityClass))
                                ->{$controllerAction}($req, $res);
                        } catch (NotFoundException $exception) {
                            $res->status()->setCode(404);
                            return $res->json(['message' => 'Not found']);
                        } catch (ValidationException $exception) {
                            $res->status()->setCode($exception->getCode());
                            return $res->json(['message' => $exception->getMessage(),'errors' => $exception->getData()]);
                        } catch (\Exception $exception) {
                            $res->status()->setCode(500);
                            return $res->json([ 'message' => $exception->getMessage() ]);
                        }

                        if (is_null($result)) {
                            $res->status()->setCode(204);
                            return $res;
                        }

                        return $res->json($result);
                    }
                );
            }
        }

        $httpServer->dispatch();
    }
}