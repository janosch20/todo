<?php

namespace Wolfi\Todo\Handler;

use Wolfi\Todo\Task;

class TaskHandler extends Handler
{
    /** @var \PDOStatement[] */
    private $stmtCache = array();

    /**
     * @param int $taskId
     * @return Task
     */
    public function getTask(int $taskId): Task
    {
        if (!isset($this->stmtCache['getTask'])) {
            $sql = "SELECT * FROM task
                    WHERE taskId = :taskId 
                    ";
            $this->stmtCache['getTask'] = $this->db->prepare($sql);
        }
        $stmt = $this->stmtCache['getTask'];
        $stmt->execute(['taskId' => $taskId]);
        if ($stmt->rowCount() < 1) {
            throw new TaskNotFoundException();
        }
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $task = new Task();
        $task->init(
            $taskId,
            $row['userId'],
            $row['taskTitle'],
            $row['taskDescription'],
            ($row['taskDone'] == 1) ? true : false,
            $row['taskCreate'],
            $row['taskEdit']
        );
        return $task;
    }

    /**
     * @param int $userId
     * @return Task[]
     */
    public function getTasksByUserId(int $userId): array
    {
        if (!isset($this->stmtCache['getTasksByUserId'])) {
            $sql = "SELECT * FROM task
                    WHERE userId = :userId 
                    ";
            $this->stmtCache['getTasksByUserId'] = $this->db->prepare($sql);
        }
        $stmt = $this->stmtCache['getTasksByUserId'];
        $stmt->execute(['userId' => $userId]);
        $ret = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $task = new Task();
            $task->init(
                $row['taskId'],
                $userId,
                $row['taskTitle'],
                $row['taskDescription'],
                ($row['taskDone'] == 1) ? true : false,
                $row['taskCreate'],
                $row['taskEdit']
            );
            $ret[] = $task;
        }
        return $ret;
    }

    /**
     * @param int $userId
     * @param string $title
     * @param string $description
     * @param bool $done
     * @return Task
     */
    public function createTask(int $userId, string $title, string $description, bool $done = false): Task
    {
        if (!isset($this->stmtCache['createTask'])) {
            $sql = "INSERT INTO task
                      (userId, taskTitle, taskDescription, taskDone)
                    VALUES 
                      (:userId, :taskTitle, :taskDescription, :taskDone)
                    ";
            $this->stmtCache['createTask'] = $this->db->prepare($sql);
        }
        $stmt = $this->stmtCache['createTask'];
        $data = [
            'userId' => $userId,
            'taskTitle' => $title,
            'taskDescription' => $description,
            'taskDone' => ($done) ? 1 : 0,
        ];
        $stmt->execute($data);
        return $this->getTask($this->db->lastInsertId());
    }

    /**
     * @param int $taskId
     * @return Task
     */
    public function setDone(int $taskId): Task
    {
        if (!isset($this->stmtCache['setDone'])) {
            $sql = "UPDATE task
                    SET taskDone = 1 
                    WHERE taskId = :taskId
                    ";
            $this->stmtCache['setDone'] = $this->db->prepare($sql);
        }
        $stmt = $this->stmtCache['setDone'];
        $stmt->execute(['taskId' => $taskId]);
        return $this->getTask($taskId);
    }

    public function updateTask(Task $task)
    {
        if (!isset($this->stmtCache['updateTask'])) {
            $sql = "UPDATE task
                    SET 
                      taskTitle = :taskTitle, 
                      taskDescription = :taskDescription,
                      taskDone = :taskDone 
                    WHERE taskId = :taskId
                    ";
            $this->stmtCache['updateTask'] = $this->db->prepare($sql);
        }
        $stmt = $this->stmtCache['updateTask'];
        $data = [
            'taskTitle' => $task->getTaskTitle(),
            'taskDescription' => $task->getTaskDescription(),
            'taskDone' => ($task->isTaskDone()) ? 1 : 0,
            'taskId' => $task->getTaskId(),
        ];
        $stmt->execute($data);
        return $task;
    }

}