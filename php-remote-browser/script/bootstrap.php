<?php
/**
 * Copyright 2009 Brandon Thomas Suit
 * Available under the BSD and MIT
 * http://possibilistic.org
 * echelon@gmail.com
 */

/**
 * System bootstrap file. 
 * Everything begins here.
 * TODO: This is very messy and seems insecure.
 * TODO (SEVERE): The html template system I wrote is VERY bad. I don't want to 
 * use something as heavy as smarty, but what I have now is terrible and lazy.
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

if(array_key_exists('gallery', $_GET)) {
	require_once 'imagepage.php';
	$title = 'Motion Detection Gallery';
	$page = 'gallery';
}
else if(array_key_exists('live', $_GET)) {
	$page = 'camview';
}

// Include HTML base file (very simple)
require_once('../html/main.php');

