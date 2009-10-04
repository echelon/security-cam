<?php

/**
 * Represents a user or hypothetical user.
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
			throw new Exception("Passhash not valid.");
		}
		$this->username = $username;
		$this->password = $password;
	public function getStatus()
	{
		return $this->status;
	}
		$this->passhash = $passhash;
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
		if(isset($this->password) {
			$this->passhash = sha1($this->password);
			return $this->passhash;
		}
		else if(isset($this->passhash)) {
			return $this->passhash;
		}
		return false;
	}
}

