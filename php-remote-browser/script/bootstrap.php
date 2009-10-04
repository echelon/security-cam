<?php

/**
 * System bootstrap file. 
 * Everything begins here.
 */

require_once('Config.php');
$config = Config::getInstance();
$config->readConfig();

require_once('Auth.php');
$auth = new Auth();
$auth->dispatch();

$user = $auth->getUser();
$status = $auth->getStatus();

$page = 'login';
$title = 'Please Authenticate';
$error = '';
switch($status) {
	case Auth::LOGGED_IN:
		$page = 'camview';
		$title = 'Camera View';
		break;
	case Auth::FAILED_LOGIN:
		$title = 'Please Authenticate Again';
		$error = 'Failed credentials';
		break;
	case Auth::REDIRECTING:
		exit();
	case Auth::NOT_LOGGED_IN:
	default:
		break;		
}

// Include HTML base file (very simple)
require_once('../html/main.php');

