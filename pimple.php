<?php


$pimple = new \Pimple\Container();

$pimple['dbHost'] = 'localhost';
$pimple['dbUser'] = 'todo_user';
$pimple['dbPassword'] = 'todo_user';

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