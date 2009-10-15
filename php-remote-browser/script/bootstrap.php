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

error_reporting(E_ALL|E_NOTICE|E_STRICT);
ini_set('display_errors', 1);

require_once('camlib/Config.php');
require_once('camlib/Auth.php');

$config = Config::getInstance();
$config->readConfig();

$auth = new Auth();
$auth->authorize();

$user = $auth->getUser();
$status = $auth->getStatus();

if(isset($_GET['firewall'])) {
	$config->setFirewalled();
}

$config->setUriPreference("remote");
if($_SERVER['REMOTE_ADDR'] == '127.0.0.1' && isset($_GET['local'])) {
	$config->setUriPreference("local");
}


/**
 * Function to return which page to show, page title, errors, etc.
 * Used by the view.
 * TODO: This is very bad/lazy code.
 */
function getPage()
{
	// XXX XXX XXX XXX BAD 
	global $status;

	// Defaults values:
	$d['page'] = 'login.php';
	$d['title'] = 'Please Authenticate';
	$d['error'] = '';

	switch($status) {
		case Auth::LOGGED_IN:

			$d['page'] = 'cam-multi.php';
			$d['title'] = 'Multi-Camera View';

			// See which page might be accessed.
			if(array_key_exists('gallery', $_GET)) {
			}
			else if(array_key_exists('viewcam', $_GET)) {
				$d['page'] = 'cam-single.php';
				$d['title'] = 'Camera View';
			}

			break;

		case Auth::FAILED_LOGIN:
			$d['title'] = 'Please Authenticate Again';
			$d['error'] = 'Failed credentials';
			$return = true;
			break;

		case Auth::REDIRECTING:
			exit(); // FIXME: Not a good semantic place for this!

		case Auth::NOT_LOGGED_IN:
		default:
			break;
	}
	return $d;
}

// Include HTML base file (very simple)
require_once('../html/main.php');

