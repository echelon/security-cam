<?php

require_once("Camera.php");
require_once("Config.php");

/**
 * Represents a video camera.
 */
class CameraDlink extends Camera
{
	/**
	 * Get the snapshot uri
	 * Note: Parameters are meaningless.
	 */
	public function jpeg($size = false, $quality = false)
	{
		// Params are meaningless
		return $this->returnUri($this->baseUri . "/image.jpg",
			 "image/jpeg");
	}

	/**
	 * Get the MJPEG uri.
	 */
	public function mjpeg()
	{
		return $this->returnUri($this->baseUri . "/mjpeg.cgi",
			 "multipart/x-mixed-replace;boundary="); // TODO: Verify mimetype
	}

	/**
	 * Doesn't have MPEG!!
	 */
	public function mpeg()
	{
		return false;
	}
}

