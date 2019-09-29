<?php

$pimple = new \Pimple\Container();

$pimple['dbHost'] = 'localhost';
$pimple['dbUser'] = 'todo_user';
$pimple['dbPassword'] = 'todo_password';

/**
 * @param \Pimple\Container $pimple
 * @return PDO
 */
$pimple['db'] = function ($pimple) {
    $dsn = "mysql:host={$pimple['dbHost']};dbname=todo;charset=utf8";
    $opt = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    return new PDO($dsn, $pimple['dbUser'], $pimple['dbPassword'], $opt);
};

/**
 * @param \Pimple\Container $pimple
 * @return \Symfony\Component\HttpFoundation\Session\Session
 */
$pimple['session'] = function ($pimple) {
    $session = new \Symfony\Component\HttpFoundation\Session\Session();
    $session->start();
    return $session;
};

/**
 * @param \Pimple\Container $pimple
 * @return \Symfony\Component\HttpFoundation\Request
 */
$pimple['request'] = function ($pimple) {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
};


/**
 * HANDLER
 */

/**
 * @param \Pimple\Container $pimple
 * @return \Wolfi\Todo\Handler\TaskHandler
 */
$pimple['taskHandler'] = function ($pimple) {
    return new \Wolfi\Todo\Handler\TaskHandler($pimple['db']);
};

/**
 * @param \Pimple\Container$pimple
 * @return \Wolfi\Todo\Handler\UserHandler
 */
$pimple['userHandler'] = function ($pimple) {
    return new \Wolfi\Todo\Handler\UserHandler($pimple['db']);
};