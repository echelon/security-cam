<?php

require_once("Config.php");

/**
 * Represents a video camera.
 */
abstract class Camera
{
	/**
	 * Local URI for viewing the camera on one's own network.
	 */
	protected $localUri = NULL;

	/**
	 * Remote URI for viewing the camera outside one's own network.
	 */
	protected $remoteUri = NULL;

	/**
	 * Model of the camera.
	 */
	protected $camModel = NULL;

	/**
	 * Camera name
	 */
	protected $camName = NULL;

	/**
	 * CTOR.
	 */
	public function __construct($camConfig)
	{
		$this->camModel = $camConfig['model'];
		$this->camName = $camConfig['name'];

		if(array_key_exists('localUri', $camConfig)) {
			$this->localUri = $camConfig['localUri'];
		}
		if(array_key_exists('remoteUri', $camConfig)) {
			$this->remoteUri = $camConfig['remoteUri'];
		}
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
	 * Return the uri (best API to use for the gui).
	 * Wraps with firewall bypass if necessary.
	 */
	public function uri() 
	{
		return $this->returnUri($this->preferredUri());
	}

	/**
	 * Uri Accessors (Unwrapped) and Mutators
	 */
	public function getLocalUri() { return $this->localUri; }
	public function getRemoteUri() { return $this->remoteUri; }
	public function setLocalUri($uri) { $this->localUri = $uri; }
	public function setRemoteUri($uri) { $this->remoteUri = $uri; }

	/**
	 * Return the camera model.
	 */
	public function getModel()
	{
		return $this->camModel;
	}

	/**
	 * Return the name of the camera.
	 */
	public function getName()
	{
		return $this->camName;
	}

	/**
	 * Gets the preferred uri.
	 */
	protected function preferredUri()
	{
		$config = Config::getInstance();
		$pref = $config->getUriPreference();

		$uri = $this->localUri;
		if(!$this->localUri) {
			$uri = $this->remoteUri;
		}
		if($pref == Config::REMOTE && $this->remoteUri) {
			$uri = $this->remoteUri;
		}
		return $uri;
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

