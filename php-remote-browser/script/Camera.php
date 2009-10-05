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
		$snap = "/img/snapshot.cgi";
		/**
		 * TODO: /img/snapshot.cgi?size=SIZE&quality=QUALITY
		* SIZE = 1-3 (optional, 3 is largest)
		* QUALITY = 1-5 (optional, 1 is highest) */

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

		$uri = "http://security.possibilistic.org/passthru.php?b&u=" . $uri;
		if($mime) {
			$uri .= "&m=" . $mime;
		}
		return $uri;
	}
}

