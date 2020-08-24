<?php

namespace Api;

use Api\Model\Task;
use Api\Providers\TaskServiceProvider;
use Api\Service\TaskService;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class App
 * @package Api
 */
Class App extends \Slim\App
{
    /**
     * App constructor.
     * @param array $container
     */
    public function __construct($container = [])
    {
        parent::__construct($container);

        // Services
        $this->getContainer()->register(new TaskServiceProvider());

        // Routes
        $this->get('/task', function (Request $request, Response $response, $args) {
            /** @var Container $this */
            $list = $this->get(TaskService::class)->fetchAll();

            return $response->withJson($list);
        });

        $this->get('/task/{id:\d+}', function (Request $request, Response $response, $args) {
            /** @var Container $this */
            if ($task = $this->get(TaskService::class)->fetch($args['id'])) {
                return $response->withJson($task);
            }
            return $response->withStatus(404);
        });

        $this->post('/task', function (Request $request, Response $response, $args) {
            try {
                $task = Task::create($request->getParams());
                /** @var Container $this */
                $task = $this->get(TaskService::class)->insert($task);

                return $response->withJson($task, 201);
            } catch (\InvalidArgumentException $e) {
                return $response->withJson(['error' => $e->getMessage()], 400);
            }
        });

        $this->put('/task/{id:\d+}', function (Request $request, Response $response, $args) {
            $task = Task::create($request->getParams());

            if (empty($task->id)) {
                return $response->withJson(['error' => 'missing id'], 400);
            }
            if ((int)$task->id !== (int)$args['id']) {
                return $response->withJson(['error' => 'id mismatch'], 400);
            }

            /** @var Container $this */
            $task = $this->get(TaskService::class)->update($task);

            return $response->withJson($task);
        });

        $this->delete('/task/{id:\d+}', function (Request $request, Response $response, $args) {
            /** @var Container $this */
            $success = $this->get(TaskService::class)->delete($args['id']);
            return $response->withJson(['success' => $success], $success ? 200 : 500);
        });
    }
}