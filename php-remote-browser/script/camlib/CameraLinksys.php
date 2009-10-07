<?php

require_once("Camera.php");
require_once("Config.php");

/**
 * Represents a video camera.
 */
class CameraLinksys extends Camera
{
	/**
	 * Get the snapshot uri
	 */
	public function jpeg($size = false, $quality = false)
	{
		// 1 smallest, 3 largest.
		switch($size) {
			case 1:
			case 2:
			case 3:
				break;
			default:
				$size = false;
		}

		// Invert quality: 1 lowest, 5 highest.
		switch($quality) {
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				$quality = abs($quality - 6);
				break;
			default:
				$quality = false;
		}

		$snap = "/img/snapshot.cgi?";

		if($size) {
			$snap .= "size=" . $size . "&";
		}
		if($quality) {
			$snap .= "quality=" . $quality;
		}

		// SIZE = 1-3 (optional, 3 is largest)
		// QUALITY = 1-5 (optional, 1 is highest)
		return $this->returnUri($this->preferredUri() . $snap,
			 "image/jpeg");
	}

	/**
	 * Get the MJPEG uri.
	 */
	public function mjpeg()
	{
		return $this->returnUri($this->preferredUri() . "/img/video.mjpeg",
			 "multipart/x-mixed-replace;boundary="); // TODO: Verify this
	}

	/**
	 * Get the MPEG uri.
	 */
	public function mpeg()
	{
		return $this->returnUri($this->preferredUri() . "/img/video.asf",
			 "video/x-ms-asf"); // TODO: Verify this
	}
}

