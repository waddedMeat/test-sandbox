<?php


use Phinx\Seed\AbstractSeed;

class TaskSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $this->table('task')->truncate();

        $data = [
            [
                'task' => 'Take out the papers and the trash',
                'is_done' => 1,
                'is_deleted' => 0,
            ],
            [
                'task' => 'Get some spending cash',
                'is_done' => 0,
                'is_deleted' => 0,
            ],
            [
                'task' => 'Scrub kitchen floor',
                'is_done' => 1,
                'is_deleted' => 1,
            ],
            [
                'task' => 'Yakety yak',
                'is_done' => 0,
                'is_deleted' => 1,
            ]
        ];

        $this->table('task')
            ->insert($data)
            ->save();
    }
}
