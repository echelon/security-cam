<?php

require_once("Config.php");

/**
 * Represents a video camera.
 */
class Camera
{
	/**
	 * Base URI where we can access the camera.
	 */
	protected $baseUri;

	/**
	 * Model of the camera.
	 */
	protected $camModel;

	/**
	 * CTOR.
	 */
	public function __construct($uri, $model)
	{
		$this->baseUri = $uri;
		$this->camModel = $model;
	}

	/**
	 * Get the base uri. 
	 * Wraps if necessary.
	 */
	public function uri() 
	{
		return $this->returnUri($this->baseUri);
	}

	/**
	 * Return the camera model.
	 */
	public function camModel()
	{
		return $this->camModel;
	}

	/**
	 * Get the snapshot uri
	 */
	public function snapshotUri($size = false, $quality = false)
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

		// TODO: /img/snapshot.cgi?size=SIZE&quality=QUALITY
		// SIZE = 1-3 (optional, 3 is largest)
		// QUALITY = 1-5 (optional, 1 is highest)

		return $this->returnUri($this->baseUri . $snap,
			 "image/jpeg");
	}

	/**
	 * Get the MJPEG uri.
	 */
	public function mjpegUri()
	{
		return $this->returnUri($this->baseUri . "/img/video.mjpeg",
			 "multipart/x-mixed-replace;boundary="); // TODO: Verify this
	}

	/**
	 * Get the MPEG uri.
	 */
	public function mpegUri()
	{
		return $this->returnUri($this->baseUri . "/img/video.asf",
			 "video/x-ms-asf"); // TODO: Verify this
	}

	/**
	 * Wrap with firewall bypass.
	 * PROTECTED.
	 */
	protected function returnUri($uri, $mime = NULL)
	{
		$config = Config::getInstance();
		if(!$config->getFirewalled()) {
			return $uri;
		}

		$uri = urlencode($uri);
		$uri = $config->firewallUri . $uri;
		if($mime) {
			$uri .= "&m=" . $mime;
		}
		return $uri;
	}
}

