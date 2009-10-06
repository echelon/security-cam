<?php

require_once("Config.php");

/**
 * Represents a video camera.
 */
abstract class Camera
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
	 * Get the JPEG snapshot uri
	 */
	abstract public function jpeg($size = false, $quality = false);

	/**
	 * Get the MJPEG uri.
	 */
	abstract public function mjpeg();

	/**
	 * Get the MPEG uri.
	 */
	abstract public function mpeg();

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

