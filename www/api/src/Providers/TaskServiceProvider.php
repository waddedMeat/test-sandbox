<?php

namespace Api\Providers;


use Api\Service\TaskService;
use PDO;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class TaskServiceProvider
 * @package Api\Providers
 */
class TaskServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple): void
    {
        $pimple[TaskService::class] = (new TaskService())
            ->setDbConnection(
                new PDO(
                    'mysql:host=mysql;dbname=' . getenv('APP_DB_NAME'),
                    getenv('APP_DB_USER'),
                    getenv('APP_DB_PASS'))
            );
    }
}