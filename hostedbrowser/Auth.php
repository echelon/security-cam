<?php
/**
 * Very rudimentary user authentication
 * Copyright 2009 Brandon Thomas Suit
 * Available under the BSD and MIT
 * http://possibilistic.org
 * echelon@gmail.com
 */

require_once('./user.php');
require_once('./config.php');

/**
 * Rudimentary Authentication system.
 */
class Auth
{
	/**
	 * Possible state list
	 */
	const $NOT_LOGGED_IN	= 0;
	const $LOGGED_IN		= 1;
	const $FAILED_LOGIN		= 2;
	const $REDIRECTING		= 3; // Logging in, out, or removing invalid cookies

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
	public function dispatch()
	{
		if(array_key_exists('username', $_POST)) {
			return $this->doLogin()
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
		$post = HttpAuth::getPost();
		if(!$post) {
			return false;
		}

		$user = User($post['username'], NULL, $post['password']);

		if(!$this->checkUserValidity($user)) {
			$this->status = self::FAILED_LOGIN;
			return false;
		}

		$this->status = self::REDIRECTING;
		HttpAuth::setCookies($user);
		HttpAuth::redirect('/'); // exits script
	}

	protected function doLogout()
	{
		$this->status = self::REDIRECTING;
		HttpAuth::unsetCookies();
		HttpAuth::redirect('/'); // exits script
	}

	protected function doVerifyCookies()
	{
		$cookies = HttpAuth::getCookies();
		$user = User($cookies['username'], $cookies['passhash']);

		if(!$this->checkUserValidity($user)) {
			$this->status = self::REDIRECTING;
			HttpAuth::unsetCookies();
			HttpAuth::redirect('/'); // exits script
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



