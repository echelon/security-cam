<?php
/**
 * Copyright 2009 Brandon Thomas Suit
 * Available under the BSD and MIT
 * http://possibilistic.org
 * echelon@gmail.com
 */

// No requires

/**
 * Represents a user or "hypothetical user" that isn't yet authenticated.
 * Can be used in the authentication/login procedure.
 */
class User
{
	private $username = "";
	private $passhash = "";
	private $password = "";

	/**
	 * Construct the user with a username and password OR passhash
	 */
	function __construct($username, $passhash = NULL, $password = NULL)
	{
		if(!$password && !$passhash) {
			throw new Exception("Password or passhash not set!");
		}
		if($passhash && strlen($passhash) != 40) {
			throw new Exception("Passhash not a valid sha1 string.");
		}
		$this->username = $username;
		$this->passhash = $passhash;
		$this->password = $password;
	}

	/**
	 * Return the username.
	 */
	function getUsername()
	{
		return $this->username;
	}

	/**
	 * Return passhash based on password.
	 */
	function getPasshash()
	{
		if(isset($this->password)) {
			$this->passhash = sha1($this->password);
			return $this->passhash;
		}
		else if(isset($this->passhash)) {
			return $this->passhash;
		}
		return false;
	}

	/**
	 * Return whether the user has set the "local" cookie.
	 * Determines which set of camera URIs to show.
	 * Cookie set by Javascript or URI parameter.
	 */
	function isLocal()
	{
		if(!array_key_exists('local', $_COOKIE)) {
			return false;
		}
		return (bool)$_COOKIE['local'];
	}

	/**
	 * Return whether the user has set the "firewall" cookie.
	 * Determines which set of camera URIs to show.
	 */
	function isFirewall()
	{
		if(!array_key_exists('firewall', $_COOKIE)) {
			return false;
		}
		return (bool)$_COOKIE['firewall'];
	}
}

