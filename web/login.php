<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../pimple.php';

$request = Request::createFromGlobals();
$session = new Session();
$session->start();

if ($session->get('todo_userId') && $session->get('todo_userName')) {
    $response = new RedirectResponse('index.php');
    $response->send();
}

$invalidCredentials = false;
if ($request->getMethod() == Request::METHOD_POST
    && strlen($request->request->get('username'))
    && strlen($request->request->get('password'))) {
    /** @var \Wolfi\Todo\Handler\UserHandler $userHandler */
    $userHandler = $pimple['userHandler'];

    try {
        if ($userHandler->authenticateUser($request->request->get('username'), $request->request->get('password'))) {
            $user = $userHandler->getUserByUserName($request->get('username'));

            $session->set('todo_userName', $user->getUserName());
            $session->set('todo_userId', $user->getUserId());
            $response = new RedirectResponse('index.php');
            $response->send();
        }
    } catch (Exception $exception) {
        // user name not found
    }
    $invalidCredentials = true;
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
    <link rel="stylesheet" href="resources/css/style.css">

    <title>Todo</title>
</head>
<body>


<div class="container">

    <div class="row margin-top-100">
        <div class="col-4 offset-4">
            <h2>Sign in</h2>
            <form method="post" action="login.php">
                <div class="form-group">
                    <label for="inputUsername">Username</label>
                    <input type="text" class="form-control <?= ($invalidCredentials) ? 'is-invalid' : '' ?>"
                           id="inputUsername" name="username" value="<?= ($request->request->get('username')) ? $request->request->get('username') : '' ?>"
                           placeholder="Enter Username" required>
                </div>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" class="form-control <?= ($invalidCredentials) ? 'is-invalid' : '' ?>"
                           id="inputPassword" placeholder="Password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <?php if ($invalidCredentials): ?>
                <div class="alert alert-danger margin-top-20" role="alert">
                    Invalid Credentials
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="resources/lib/jquery/jquery.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>-->
<script src="resources/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>