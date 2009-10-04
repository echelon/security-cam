<?php
/**
 * Copyright 2009 Brandon Thomas Suit
 * Available under the BSD and MIT
 * http://possibilistic.org
 * echelon@gmail.com
 */

require_once('Config.php');

/**
 * A set of helper methods that deal with cookies/post data, etc.
 * Note that these *only* do the bare minimum for helping user authentication.
 * TODO: expand this to help with more HTTP concerns, or make a **pythonic** 
 * wrapper for all of PHP's various functions for HTTP, server, filesystem, etc 
 * to reuse in various future projects (that entails a lot more work and
 * requires time).
 */
class HttpHelper
{
	/**
	 * Redirect the browser and exit script.
	 */
	static public function redirect($location = '/')
	{
		header('Location: ' . $location);
		exit();
	}

	/**
	 * Get username/password post data or false.
	 */
	static public function getPost()
	{
		if(!array_key_exists('username', $_POST) && 
			!array_key_exists('password', $_POST)) {
				return false;
		}
		return array(
			'username' => $_POST['username'],
			'password' => $_POST['password']
		);
	}

	/**
	 * Get cookie data, false if not set.
	 */
	static public function getCookies()
	{
		if(!array_key_exists('username', $_COOKIE) && 
			!array_key_exists('passhash', $_COOKIE)) {
				return false;
		}
		return array(
			'username' => $_COOKIE['username'],
			'passhash' => $_COOKIE['passhash']
		);
	}

	/**
	 * Set a valid cookie for a user.
	 */
	static public function setCookies(User $user)
	{
		$config = Config::getInstance();
		$expire = time() + 60*60*24 * (int)$config->cookieDays;

		setcookie('username', $user->getUsername(), $expire, '/', 
					$config->domain);
		setcookie('passhash', $user->getPasshash(), $expire, '/', 
					$config->domain);
	}

	/**
	 * Unset the cookies.
	 */
	static public function unsetCookies()
	{
		$config = Config::getInstance();
		$expire = time() - 1000000000;
		setcookie('username', '', $expire, '/', $config->domain);
		setcookie('passhash', '', $expire, '/', $config->domain);
	}
}

