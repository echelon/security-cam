<?php

require_once('User.php');

/**
 * Store configurations and valid users.
 * This is a singleton, so use getInstance() to get the config object.
 * Access configuration data with array key operators, eg $configs['key'].
 */
class Config
{
	/**
	 * Singleton instance.
	 */
	private static $instance = NULL;

	/**
	 * System configurations.
	 */
	private $configs = array();

	/**
	 * List of users.
	 */
	private $userlist = array();

	/**
	 * If the configs file has already been read.
	 */
	private $configsRead = false;

	/**
	 * Singleton/Uncallable CTOR.
	 */
	private function __construct() {}

	/**
	 * Get the singleton instance.
	 */
	public static function getInstance()
	{
		if(!self::$instance) {
			self::$instance = new Config();
		}
		return self::$instance;
	}

	/**
	 * Read the typical config files.
	 * Configs can only exist in ../myconfigs.php or ../configs.php
	 */
	public function readConfig()
	{
		if($this->configsRead) {
			return;
		}
		$this->configsRead = true;

		if(file_exists('../myconfigs.php')) {
			require_once('../myconfigs.php');
			$this->setConfigs($configs);
			$this->addUsers($users);
			return;
		}

		if(file_exists('../configs.php')) {
			require_once('../configs.php');
			$this->setConfigs($configs);
			$this->addUsers($users);
			return;
		}

		echo "CAN'T READ CONFIG!";
		exit();
	}

	/**
	 * Set configs.
	 * Overrides previous values.
	 */
	public function setConfigs($configs)
	{
		if(!is_array($configs)) {
			return;
		}

		foreach($configs as $key => $val) {
			if(!is_string($key)) {
				continue;
			}
			$this->configs[$key] = $val;
		}
	}

	/**
	 * Get the config data by its key, else false.
	 */
	public function __get($key)
	{
		if(array_key_exists($key, $this->configs)) {
			return $this->configs[$key];
		}
		return false;
	}

	/**
	 * Return whether the config key exists.
	 */
	public function __isset($key)
	{
		return array_key_exists($key, $this->configs);
	}

	/**
	 * Add a user to the configs.
	 * Can be a 'User' object or an array with 'username' and 'passhash' keys.
	 */
	public function addUser($user)
	{
		if(!($user instanceof User) || !is_array($user)) {
			return;
		}
		if(is_array($user)) {
			if(!array_key_exists('username', $user) || 
			   !array_key_exists('passhash', $user)) {
					return;
			}
			$user = User($user['username'], $user['passhash']);
		}

		if(in_array($user, $this->userlist)) {
			return;
		}
		$this->userlist[] = $user;
	}

	/**
	 * Add an array of users to the configs.
	 * User array can contain 'User' objects and/or arrays with 'username' and
	 * 'passhash' keys.
	 */
	public function addUsers(array $users)
	{
		foreach($users as $user) {
			if(!($user instanceof User) && !is_array($user)) {
				continue;
			}
			if(is_array($user)) {
				if(!array_key_exists('username', $user) || 
				   !array_key_exists('passhash', $user)) {
						continue;
				}
				$user = new User($user['username'], $user['passhash']);
			}

			if(in_array($user, $this->userlist)) {
				continue;
			}
			$this->userlist[] = $user;
		}
	}

	/**
	 * Get the user with the username if it exists, else false.
	 * User can be a 'User' instance or a string.
	 */
	public function getUser($user)
	{
		$targetName = "";
		if($user instanceof User) {
			$targetName = $user->getUsername();
		}
		else if(is_string($user)) {
			$targetName = $user;
		}

		if(!$targetName) {
			return false;
		}

		foreach($this->userlist as $u) {
			$uname = $u->getUsername();
			if(!$uname) {
				continue;
			}
			if($uname == $targetName) {
				return $u;
			}
		}
		return false;
	}
}

