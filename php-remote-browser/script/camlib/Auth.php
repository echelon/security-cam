<?php
/**
 * Copyright 2009 Brandon Thomas Suit
 * Available under the BSD and MIT
 * http://possibilistic.org
 * echelon@gmail.com
 */

require_once('User.php');
require_once('Config.php');
require_once('HttpHelper.php');

/**
 * A very rudimentary user authentication class.
 * Handles the entire authentication process by calling dispatch().
 * Can also be used to get the user object (of a logged in and valid user) as
 * well as the authentication state: getStatus() and getUser() respectively.
 * TODO: Better error system.
 */
class Auth
{
	/**
	 * Possible state list
	 */
	const NOT_LOGGED_IN	= 0;
	const LOGGED_IN		= 1;
	const FAILED_LOGIN	= 2;
	const REDIRECTING	= 3; // Logging in, out, or removing invalid cookies

	/**
	 * Credential status.
	 * One of the possible states above.
	 */
	private $status = 0;

	/**
	 * Authenticated user.
	 * Only set if the browser gave us valid cookies.
	 */
	private $user = NULL;

	/**
	 *  CTOR.
	 */
	public function __construct()
	{
		// Nothing
	}

	/**
	 * Dispatch to the appropriate authentication procedure function.
	 * May exit the script in some cases (eg. HTTP redirect).
	 */
	public function authorize()
	{
		if(array_key_exists('username', $_POST)) {
			return $this->doLogin();
		}
		if(array_key_exists('logout', $_GET)) {
			return $this->doLogout();
		}
		if(array_key_exists('username', $_COOKIE)) {
			return $this->doVerifyCookies();
		}
	}

	/**
	 * Return the user's credential status.
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Return the verified logged in user, if there is one.
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Called if login procedure initiated.
	 */
	protected function doLogin()
	{
		$post = HttpHelper::getPost();
		if(!$post) {
			return false;
		}

		$user = NULL;
		try {
			$user = new User($post['username'], NULL, $post['password']);
		}
		catch(Exception $e) {}

		if(!$user || !$this->checkUserValidity($user)) {
			$this->status = self::FAILED_LOGIN;
			return false;
		}

		$this->status = self::REDIRECTING;
		HttpHelper::setCookies($user);
		HttpHelper::redirect('/'); // exits script
	}

	protected function doLogout()
	{
		$this->status = self::REDIRECTING;
		HttpHelper::unsetCookies();
		HttpHelper::redirect('/'); // exits script
	}

	protected function doVerifyCookies()
	{
		$cookies = HttpHelper::getCookies();
		$user = NULL;
		try {
			$user = new User($cookies['username'], $cookies['passhash']);
		}
		catch(Exception $e) {}

		if(!$user || !$this->checkUserValidity($user)) {
			$this->status = self::REDIRECTING;
			HttpHelper::unsetCookies();
			HttpHelper::redirect('/'); // exits script
			return false;
		}
		$this->user = $user;
		$this->status = self::LOGGED_IN;
		return true;
	}

	/**
	 * Check the user against the configuration userlist
	 */
	protected function checkUserValidity(User $user)
	{
		$config = Config::getInstance();
		$u = $config->getUser($user);
		if(!$u) {
			return false;
		}

		if($user->getPasshash() != $u->getPasshash()) {
			return false;
		}
		return true;
	}
}



