<?php

namespace Api\Model;

/**\
 * Class Task
 * @package Api\Model
 */
class Task
{
    public $id;
    public $task;
    public $is_done = 0;
    public $is_deleted = 0;
    public $created;
    public $modified;

    /**
     * @param array $args
     * @return Task
     */
    public static function create(array $args): Task
    {
        $args = array_intersect_key($args, get_class_vars(__CLASS__));
        $task = new self();
        foreach ($args as $key => $val) {
            $task->$key = $val;
        }
        return $task;
    }

}