<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

//error_reporting(E_ALL);
//ini_set('display_errors', true);

require_once __DIR__ . '/../vendor/autoload.php';

$session = new Session();
$session->start();
$session->invalidate();
$response = new RedirectResponse('login.php');
$response->send();