<?php

// Configurations for the system (replace as desired).
$configs = array
(
	// Domain name this script is hosted at
	'domain'		=> $_SERVER['HTTP_HOST'],

	// Firewall passthrough location
	'firewallUri'	=> "http://DOMAIN/passthru.php?b&u=",

	// Days the cookie should last
	'cookieDays'	=> 10,	
);

// Cameras
$cameras = array();
$cameras[] = array('model' => 'camera model',
				   'name'  => 'memorable name for camera',
				   'localUri'  => 'camera uri (eg. IP) inside your network',
				   'remoteUri' => 'camera uri from outside your network');

// Valid users for the system, passhash is sha1 (unsalted).
$users = array();
$users[] = array('username' => 'test', 
				 'passhash' => 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3');
$users[] = array('username' => 'test2', 
				 'passhash' => '109f4b3c50d7b0df729d299bc6f8e9ef9066971f');


