<?php

require_once '../vendor/autoload.php';

use RestfulAdmin\App;
use RestfulAdmin\Module\Module;
use RestfulAdmin\Route\Route;
use RestfulAdmin\DataProvider\Mongo;

(new Dotenv\Dotenv(dirname(__DIR__)))->load();

(new App)
    ->setDataProvider(new Mongo($_ENV['MONGO_URI'], $_ENV['MONGO_DB']))
    ->setModules([
        (new Module)
            ->setName('tasks')
            ->setEntityClass(\RestfulAdmin\Entity\Task::class)
            ->setControllerClass(\RestfulAdmin\Controller\RestfulController::class)
            ->setRoutes([
                new Route('GET', '', 'index'),
                new Route('POST', '', 'create'),
                new Route('GET', '/[:id]', 'find'),
                new Route('PUT', '/[:id]', 'update'),
                new Route('DELETE', '/[:id]', 'delete'),
            ])
    ])
    ->start();