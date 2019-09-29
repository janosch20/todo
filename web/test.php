<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../pimple.php';

//\Symfony\Component\Debug\Debug::enable();

$taskHandler = $pimple['taskHandler'];

/** @var \Wolfi\Todo\Handler\UserHandler $userHandler */
$userHandler = $pimple['userHandler'];
//dump($userHandler);

//$user = $userHandler->createUser('test', 'test@testmail.com', 'test');
//dump($user);

//dump($userHandler->authenticateUser('test', 'test'));

/** @var \Wolfi\Todo\Handler\TaskHandler $taskHandler */
$taskHandler = $pimple['taskHandler'];

//$task = $taskHandler->createTask($user->getUserId(), 'title', 'asdfsadfsad f');
//dump($task);

//dump($taskHandler->setDone(5));

//dump($taskHandler->getTasksByUserId(2));

$task = $taskHandler->getTask(3);
dump($task, json_encode($task->jsonSerialize()));