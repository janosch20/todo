<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../pimple.php';

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** @var Request $request */
$request = $pimple['request'];
/** @var \Symfony\Component\HttpFoundation\Session\Session $session */
$session = $pimple['session'];
/** @var \Wolfi\Todo\Handler\UserHandler $userHandler */
$userHandler = $pimple['userHandler'];

if ($request->getMethod() !== Request::METHOD_POST) {
    $response = new JsonResponse(['result' => false], Response::HTTP_METHOD_NOT_ALLOWED);
    $response->send();
    die();
}

if (!strlen($request->request->get('task_id'))) {
    $response = new JsonResponse(['result' => false], Response::HTTP_BAD_REQUEST);
    $response->send();
    die();
}

if ($userHandler->isLoggedIn($session)) {
    try {
        /** @var \Wolfi\Todo\Handler\TaskHandler $taskHandler */
        $taskHandler = $pimple['taskHandler'];
        $taskHandler->setDone($request->request->get('task_id'), $session->get('todo_userId'));
        $response = new JsonResponse(['result' => true]);
    } catch (Exception $exception) {
        $response = new JsonResponse(['result' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
} else {
    $response = new JsonResponse(['result' => false], Response::HTTP_FORBIDDEN);
}
$response->send();