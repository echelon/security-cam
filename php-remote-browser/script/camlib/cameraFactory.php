<?php

require_once("CameraDlink.php");
require_once("CameraLinksys.php");

/**
 * Camera Factory.
 * Returns correct Camera subclass.
 */
function cameraFactory($camConfig)
{
	switch($camConfig['model']) {
		case 'dlink-dcs920':
			return new CameraDlink($camConfig);
		case 'linksys-wvc54gca':
			return new CameraLinksys($camConfig);
		default:
			return false;
	}
}

