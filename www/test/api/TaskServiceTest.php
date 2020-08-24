<?php

namespace Api\Service;

use Api\App;
use Api\Model\Task;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Class TaskServiceTest
 * @package Api\Service
 */
class TaskServiceTest extends \PHPUnit\Framework\TestCase
{

    /** @var TaskService */
    private $subject;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $phinx = new PhinxApplication();
        $phinx->setAutoExit(false);
        $phinx->run(new StringInput('seed:run -e testing -s TaskSeed'), new NullOutput());

        $app = new App();
        $this->subject = $app->getContainer()->get(TaskService::class);
    }

    /**
     * @return array
     */
    public function provideBadInsertData(): array
    {
        return [
            [
                'empty' => new Task()
            ],
            [
                'existing task' => Task::create(['id' => 1])
            ],
            [
                'missing task' => Task::create(['id' => 9999, 'is_deleted' => 0, 'is_done' => 0])
            ],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideBadInsertData
     * @param $given
     */
    public function testInsertFailureTrowsException($given): void
    {
        $this->subject->insert($given);
    }

    /**
     *
     */
    public function testFetchAllWithoutDeleted(): void
    {
        $tasks = $this->subject->fetchAll();

        $this->assertIsArray($tasks);
        $this->assertNotEmpty($tasks);

        foreach ($tasks as $task) {
            $this->assertInstanceOf(Task::class, $task);
            $this->assertEquals(0, $task->is_deleted);
        }
    }

    /**
     *
     */
    public function testFetchAllWithDeleted(): void
    {
        $tasks = $this->subject->fetchAll(true);

        $this->assertIsArray($tasks);

        // only deleted tasks
        $tasks = array_filter($tasks, function ($o) {
            return (int)$o->is_deleted === 1;
        });

        $this->assertNotEmpty($tasks);

        foreach ($tasks as $task) {
            $this->assertInstanceOf(Task::class, $task);
        }


    }

    /**
     *
     */
    public function testFetch(): void
    {
        $task = $this->subject->fetch(1);
        $this->assertInstanceOf(Task::class, $task);

        $task = $this->subject->fetch(0);
        $this->assertNull($task);
    }

    /**
     *
     */
    public function testDelete(): void
    {
        $task = Task::create(['task' => uniqid(null, true)]);

        $this->subject->insert($task);
        $this->assertNotEmpty($task->id, 'id is added to task on insert');

        $task = $this->subject->fetch($task->id);
        $this->assertInstanceOf(Task::class, $task, 'task is persisted');
        $this->assertEquals(0, $task->is_deleted, 'task not deleted');

        $success = $this->subject->delete($task->id);
        $this->assertTrue($success, 'no errors from delete');

        $task = $this->subject->fetch($task->id);
        $this->assertInstanceOf(Task::class, $task, 'task exists');
        $this->assertEquals(1, $task->is_deleted, 'task flagged as deleted');
    }

    /**
     * @return array
     */
    public function provideBadUpdateData(): array
    {
        return [
            [
                'empty' => new Task()
            ],
            [
                'missing task' => Task::create(['id' => 1])
            ],
            [
                'missing id' => Task::create(['task' => 'something cool', 'is_deleted' => 0, 'is_done' => 0])
            ],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideBadUpdateData
     * @param $given
     */
    public function testFailedUpdate($given): void
    {
        $this->subject->update($given);
    }
}
