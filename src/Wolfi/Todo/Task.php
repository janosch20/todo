<?php

namespace Wolfi\Todo;

class Task implements \JsonSerializable
{
    /** @var int */
    private $taskId;

    /** @var int */
    private $userId;

    /** @var string */
    private $taskTitle;

    /** @var string */
    private $taskDescription;

    /** @var bool */
    private $taskDone;

    /** @var string */
    private $taskCreate;

    /** @var string */
    private $taskEdit;


    /**
     * @param int $taskId
     * @param int $userId
     * @param string $taskTitle
     * @param string $taskDescription
     * @param bool $taskDone
     * @param string $taskCreate
     * @param string $taskEdit
     */
    public function init(
        int $taskId,
        int $userId,
        string $taskTitle,
        string $taskDescription,
        bool $taskDone,
        string $taskCreate,
        string $taskEdit
    ) {
        $this->taskId = $taskId;
        $this->userId = $userId;
        $this->taskTitle = $taskTitle;
        $this->taskDescription = $taskDescription;
        $this->taskDone = $taskDone;
        $this->taskCreate = $taskCreate;
        $this->taskEdit = $taskEdit;
    }

    /**
     * @return int
     */
    public function getTaskId(): int
    {
        return $this->taskId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getTaskTitle(): string
    {
        return $this->taskTitle;
    }

    /**
     * @return string
     */
    public function getTaskDescription(): string
    {
        return $this->taskDescription;
    }

    /**
     * @return bool
     */
    public function isTaskDone(): bool
    {
        return $this->taskDone;
    }

    /**
     * @return string
     */
    public function getTaskCreate(): string
    {
        return $this->taskCreate;
    }

    /**
     * @return string
     */
    public function getTaskEdit(): string
    {
        return $this->taskEdit;
    }

    public function jsonSerialize()
    {
        return [
            'taskId' => $this->taskId,
            'taskTitle' => $this->taskTitle,
            'taskDescription' => $this->taskDescription,
            'taskDone' => $this->taskDone,
            'taskCreate' => $this->taskCreate,
            'taskEdit' => $this->taskEdit,
        ];
    }
}