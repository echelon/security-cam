<?php

// Configurations for the system (replace as desired).
$configs = array
(
	// Domain name this script is hosted at
	'domain'		=> $_SERVER['HTTP_HOST'],

	// Days the cookie should last
	'cookieDays'	=> 10,	
);

// Cameras
$cameras = array();
$cameras[] = array('uri' => 'location',
				   'model'	=> 'type');

// Valid users for the system, passhash is sha1 (unsalted).
$users = array();
$users[] = array('username' => 'test', 
				 'passhash' => 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3');
$users[] = array('username' => 'test2', 
				 'passhash' => '109f4b3c50d7b0df729d299bc6f8e9ef9066971f');


