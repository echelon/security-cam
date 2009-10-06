<?php

require_once("CameraDlink.php");
require_once("CameraLinksys.php");

/**
 * Camera Factory.
 * Returns correct Camera subclass.
 */
function cameraFactory($url, $model)
{
	switch($model) {
		case 'dlink-dcs920':
			return new CameraDlink($url, $model);
		case 'linksys-wvc54gca':
			return new CameraLinksys($url, $model);
		default:
			return false;
	}
}

