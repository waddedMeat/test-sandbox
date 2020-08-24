<?php

namespace Api\Service;

use Api\Model\Task;
use PDO;

/**
 * Class TaskService
 * @package Api\Service
 */
class TaskService
{
    /** @var PDO */
    private $db;

    /**
     * @param PDO $conn
     * @return TaskService
     */
    public function setDbConnection(PDO $conn): TaskService
    {
        $this->db = $conn;
        return $this;
    }

    /**
     * @param Task $task
     * @return Task|null
     */
    public function insert(Task $task): ?Task
    {
        if (empty($task->task)) {
            throw new \InvalidArgumentException('Task is missing required field');
        }
        $stmt = $this->db->prepare('INSERT INTO task (task) VALUES (?)');
        $stmt->execute([$task->task]);

        $task->id = $this->db->lastInsertId();
        $task->created = $task->modified = date('c');

        return $task;
    }

    /**
     * @param Task $task
     * @return Task
     */
    public function update(Task $task): Task
    {
        switch (true) {
            case empty($task->id):
            case empty($task->task):
                throw new \InvalidArgumentException('Task is missing required field');
            default:
                // pass
        }
        $stmt = $this->db->prepare('UPDATE task SET task = ?, is_done = ?, is_deleted = ? WHERE id = ?');
        $stmt->execute([$task->task, $task->is_done, $task->is_deleted, $task->id]);
        $task->modified = date('c');

        return $task;
    }

    /**
     * @param int $id
     * @return Task|null
     */
    public function fetch(int $id): ?Task
    {
        $stmt = $this->db->prepare('SELECT * FROM task WHERE id = ?');
        $stmt->execute([$id]);

        return $stmt->fetchObject(Task::class) ?: null;
    }

    /**
     * @param bool $withDeleted include deleted records in results
     * @return array
     */
    public function fetchAll(bool $withDeleted = false): array
    {
        $stmt = $this->db->prepare('SELECT * FROM task WHERE is_deleted IN (0, ?)');
        $stmt->execute([(int)$withDeleted]);

        $list = [];
        while ($obj = $stmt->fetchObject(Task::class)) {
            $list[] = $obj;
        }
        return $list;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE task SET is_deleted =1 WHERE id = ?');
        return (bool)$stmt->execute([$id]);
    }
}

