<?php

//error_reporting(E_ALL);
//ini_set('display_errors', true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../pimple.php';

/** @var \Wolfi\Todo\Handler\UserHandler $userHandler */
$userHandler = $pimple['userHandler'];
/** @var \Symfony\Component\HttpFoundation\Session\Session $session */
$session = $pimple['session'];

if ($userHandler->isLoggedIn($session)) {
    try {
        $user = $userHandler->getUser($session->get('todo_userId'));
    } catch (Exception $exception) {
        $session->invalidate();
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse('login.php');
        $response->send();
    }
} else {
    $session->invalidate();
    $response = new \Symfony\Component\HttpFoundation\RedirectResponse('login.php');
    $response->send();
}

/** @var \Wolfi\Todo\Handler\TaskHandler $taskHandler */
$taskHandler = $pimple['taskHandler'];
$request = $pimple['request'];
if (strlen($request->request->get('title'))) {
    $title = $request->request->get('title');
    $description = $request->request->get('description');
    $taskHandler->createTask($user->getUserId(), $title, $description);
}


?>


<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="resources/lib/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="resources/lib/fontawesome/css/all.css">

    <link rel="stylesheet" href="resources/css/style.css">

    <title>Todo</title>
</head>
<body>

<div class="container">

    <div class="row margin-top-20">
        <div class="col">
            <p class="float-right">
                logged in as <span class="font-weight-bold text-primary"><?= $user->getUserName() ?></span>
                <a class="btn btn-outline-primary margin-left-10" href="logout.php">LOGOUT</a>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <h2 class="text-primary">Add Task</h2>
            <form method="post" action="index.php">
                <div class="form-group">
                    <label for="inputTitle">Email address</label>
                    <input type="text" class="form-control" name="title" id="inputTitle" placeholder="Enter title">
                </div>
                <div class="form-group">
                    <label for="inputDescription">Example textarea</label>
                    <textarea class="form-control" id="inputDescription" name="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    <div class="row margin-top-50">
        <div class="col">
            <h2 class="text-primary">Your Tasks</h2>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="inputShowAll">
                <label class="form-check-label" for="inputShowAll">Show all</label>
            </div>
            <table id="task-table" class="table table-striped table-hover">
                <thead>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                </thead>
                <tbody>
                <?php foreach ($taskHandler->getTasksByUserId($user->getUserId()) as $task): ?>
                    <tr style="<?= ($task->isTaskDone()) ? 'display: none;' : '' ?>" class="task-row" data-task-done="<?= ($task->isTaskDone()) ? 'true' : 'false' ?>" data-task-id="<?= $task->getTaskId() ?>">
                        <td><?= $task->getTaskTitle() ?></td>
                        <td><?= $task->getTaskDescription() ?></td>
                        <td>
                            <?php if ($task->isTaskDone()): ?>
                                <span class="badge badge-success" data-badge-id="<?= $task->getTaskId() ?>">done</span>
                            <?php else: ?>
                                <span class="badge badge-warning" data-badge-id="<?= $task->getTaskId() ?>">open</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$task->isTaskDone()): ?>
                                <button class="btn btn-primary btnSetDone" data-task-id="<?= $task->getTaskId() ?>">
                                    <i class="fas fa-check"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="resources/lib/jquery/jquery.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>-->
<script src="resources/lib/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="resources/js/index.js"></script>
</body>
</html>