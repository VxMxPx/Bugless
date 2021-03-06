<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

/**
 * Avrelia
 * ----
 * Database Session Driver
 * ----
 * @package    Avrelia
 * @author     Avrelia.com
 * @copyright  Copyright (c) 2010, Avrelia.com
 * @license    http://framework.avrelia.com/license
 * @link       http://framework.avrelia.com
 * @since      Version 0.80
 * @since      2012-03-27
 */
class cSessionDriverDb implements cSessionDriverInterface
{
	private $CurrentUser;		# array
	private $loggedIn = false;	# boolean


	/**
	 * Will construct the database object.
	 * --
	 * @return	void
	 */
	public function __construct()
	{
		# Try to find sessions
		$this->sessionDiscover();
	}
	//-

	/**
	 * Create all files / tables required by this plug to work
	 * --
	 * @return	boolean
	 */
	public static function _create()
	{
		# Create users table (if doesn't exists)
		if (Plug::Has('database')) {
			cDatabase::Execute(Cfg::Get('plugs/session/db/Tables/users_table'));
			cDatabase::Execute(Cfg::Get('plugs/session/db/Tables/sessions_table'));
		}
		else {
			trigger_error("Can't create, database plug must be enabled.", E_USER_ERROR);
			return false;
		}

		$Defaults = Cfg::Get('plugs/session/defaults');

		foreach ($Defaults as $DefUser)
		{
			$DefUser['password'] = vString::Hash($DefUser['password'], false, true);
			cDatabase::Create($DefUser,  Cfg::Get('plugs/session/users_table'));
		}

		return true;
	}
	//-

	/**
	 * Destroy all elements created by this plug
	 * --
	 * @return	boolean
	 */
	public static function _destroy()
	{
		cDatabase::Execute('DROP TABLE IF EXISTS ' . Cfg::Get('plugs/session/users_table'));
		cDatabase::Execute('DROP TABLE IF EXISTS ' . Cfg::Get('plugs/session/sessions_table'));
		return true;
	}
	//-


	/**
	 * Will process (clean) user's agent.
	 * --
	 * @param	string	$agent
	 * --
	 * @return	string
	 */
	private static function cleanAgent($agent)
	{
		return vString::Clean(str_replace(' ', '_', $agent), 200, 'aA1c', '_');
	}
	//-

	/**
	 * Return user's information as an array. If key provided, then only particular
	 * info can be returned. For example $key = uname
	 * --
	 * @param	string	$key
	 * --
	 * @return	mixed
	 */
	public function asArray($key=false)
	{
		if (!$key) {
			return $this->CurrentUser;
		}
		else {
			return isset($this->CurrentUser[$key]) ? $this->CurrentUser[$key] : false;
		}
	}
	//-

	/*  ****************************************************** *
	 *          Login / Logout / isLoggedin
	 *  **************************************  */

	/**
	 * Login the user
	 * --
	 * @param	string	$username
	 * @param	string	$password
	 * @param	boolean	$rememberMe	If set to false, session will expire when user
	 * 								close browser's window.
	 * --
	 * @return	boolean
	 */
	public function login($username, $password, $rememberMe=true)
	{
		$return = $this->userSet($username, $password);

		if ($return) {
			$this->sessionSet($this->CurrentUser['id'], $rememberMe);
			return true;
		}
		else {
			return false;
		}
	}
	//-

	/**
	 * Will log-in user based on id.
	 * --
	 * @param	integer	$id
	 * @param	boolean	$rememberMe
	 * --
	 * @return	boolean
	 */
	public function loginId($id, $rememberMe=true)
	{
		$return = $this->userSet($id);

		if ($return) {
			$this->sessionSet($this->CurrentUser['id'], $rememberMe);
			return true;
		}
		else {
			return false;
		}
	}
	//-

	/**
	 * Will logout current user
	 * ---
	 * @return	void
	 */
	public function logout()
	{
		if ($this->loggedIn) {
			$this->sessionDestroy($this->CurrentUser['id']);
			$this->CurrentUser    = false;
			$this->loggedIn       = false;
		}
	}
	//-

	/**
	 * Is user logged in?
	 * ---
	 * @return	boolean
	 */
	public function isLoggedin()
	{
		# All this must be true.
		return $this->CurrentUser && $this->loggedIn;
	}
	//-

	/**
	 * Will reload current user's informations; Useful after an update.
	 * --
	 * @return	void
	 */
	public function reload()
	{
		if ($this->isLoggedin()) {
			$this->userSet($this->CurrentUser['id']);
		}
	}
	//-

	/*  ****************************************************** *
	 *          User Methods
	 *  **************************************  */

	/**
	 * Check if user can be found, and if is active. If both is true, user will
	 * be set (logged in).
	 * --
	 * @param	mixed	$user		User's ID or username must be provided
	 * @param	string	$password	If you entered password, then you must provide
	 * 								username as $user, else you *must* provide id.
	 * --
	 * @return	boolean
	 */
	private function userSet($user, $password=false)
	{
		# Select user
		if ($password) {
			$User = cDatabase::Find(Cfg::Get('plugs/session/db/users_table'), array('uname' => $user));
		}
		else {
			$User = cDatabase::Find(Cfg::Get('plugs/session/db/users_table'), array('id' => (int)$user));
		}

		# Valid user?
		if ($User->failed()) {
			Log::Add('INF', "Invalid username/id entered, user not found: `{$user}`.", __LINE__, __FILE__);
			return false;
		}

		$User = $User->asArray(0);

		if (!$User['active']) {
			Log::Add('INF', "User's account is not active.", __LINE__, __FILE__);
			return false;
		}

		if ($password) {
			if ($User['password'] !== vString::Hash($password, $User['password'], true)) {
				Log::Add('INF', "Invalid password entered for: `{$user}`.", __LINE__, __FILE__);
				return false;
			}
		}

		Log::Add('INF', "User logged in: `{$User['uname']}`.", __LINE__, __FILE__);
		$this->CurrentUser = $User;
		$this->loggedIn = true;

		return true;
	}
	//-

	/*  ****************************************************** *
	 *          Session Methods
	 *  **************************************  */

	/**
	 * Will seek for user's session!
	 * If one is found, the user will be auto-logged in, and true for this function
	 * will be returned, else false will be returned.
	 * --
	 * @return	boolean
	 */
	private function sessionDiscover()
	{
		# Check if we can find session id in cookies.
		if ($sessionId = uCookie::Read(Cfg::Get('plugs/session/cookie_name')))
		{
			# Look for session
			$SessionDetails = cDatabase::Find(
									Cfg::Get('plugs/session/db/sessions_table'),
									array('id' => vString::Clean($sessionId, 400, 'aA1c', '_'))
								);

			# Okey we have something, check it...
			if ($SessionDetails->succeed())
			{
				$SessionDetails = $SessionDetails->asArray(0);
				$userId  = $SessionDetails['user_id'];
				$expires = $SessionDetails['expires'];
				$ip      = $SessionDetails['ip'];
				$agent   = $SessionDetails['agent'];

				# Check if it is expired?
				if ($expires < time()) {
					Log::Add('INF', "Session was found, but it's expired.", __LINE__, __FILE__);
					$this->sessionDestroy($sessionId);
					return false;
				}

				# Do we have to match IP address?
				if (Cfg::Get('plugs/session/require_ip')) {
					if ($ip !== $_SERVER['REMOTE_ADDR']) {
						Log::Add('INF', "The IP from session file: `{$ip}`, doesn't match with actual IP: `{$_SERVER['REMOTE_ADDR']}`.", __LINE__, __FILE__);
						$this->sessionDestroy($sessionId);
						return false;
					}
				}

				# Do we have to match agent?
				if (Cfg::Get('plugs/session/require_agent')) {
					$currentAgent = self::cleanAgent($_SERVER['HTTP_USER_AGENT']);

					if ($agent !== $currentAgent) {
						Log::Add('INF', "The agent from session file: `{$agent}`, doesn't match with actual agent: `{$currentAgent}`.", __LINE__, __FILE__);
						$this->sessionDestroy($sessionId);
						return false;
					}
				}

				# Try to set user now...
				if (!$this->userSet($userId)) {
					return false;
				}

				# Remove old session in any case
				$this->sessionsClearExpired();

				return true;
			}
		}
		else {
			Log::Add('INF', "No session found!", __LINE__, __FILE__);
			return false;
		}
	}
	//-

	/**
	 * Set session (set cookie, add info to sessions file)
	 * --
	 * @param	string	$userId
	 * @param	boolean	$rememberMe	If set to false, session will expire when user
	 * 								close browser's window.
	 * @return	boolean
	 */
	private function sessionSet($userId, $rememberMe=true)
	{
		# Set expires to some time in future. It 0 was set in config, then we
		# set it to expires imidietly when browser window is closed.
		if ($rememberMe === false) {
			$expires = 0;
		}
		else {
			$expires = (int) Cfg::Get('plugs/session/expires');
			$expires = $expires > 0 ? $expires + time() : 0;
		}

		# Create unique id
		$qId  = time() . '_' . vString::Random(20, 'aA1');

		# Store cookie
		uCookie::Create(Cfg::Get('plugs/session/cookie_name'), $qId, $expires);

		# Set session file
		$Session = array(
			'id'      => $qId,
			'user_id' => $userId,
			'expires' => $expires === 0 ? time() + 60 * 60 : $expires,
			'ip'      => $_SERVER['REMOTE_ADDR'],
			'agent'   => self::cleanAgent($_SERVER['HTTP_USER_AGENT']),
		);

		return cDatabase::Create($Session, Cfg::Get('plugs/session/db/sessions_table'))->succeed();
	}
	//-

	/**
	 * Used mostly on logout, will remove session's cookies and unset it in file.
	 * --
	 * @param	string	$sessionId
	 * --
	 * @return	boolean
	 */
	private function sessionDestroy($userId)
	{
		# Remove cookies
		uCookie::Remove(Cfg::Get('plugs/session/cookie_name'));

		# Okay, clear session now...
		return cDatabase::Delete(Cfg::Get('plugs/session/db/sessions_table'), array('user_id' => (int) $userId))->succeed();
	}
	//-

	/**
	 * Will clear all expired sessions.
	 * --
	 * @return	void
	 */
	private function sessionsClearExpired()
	{
		cDatabase::Delete(
			Cfg::Get('plugs/session/db/sessions_table'),
			'WHERE expires < :expires',
			array('expires' => time())
		);
	}
	//-
}
//--
