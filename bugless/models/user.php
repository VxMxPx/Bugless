<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

/**
 * Avrelia
 * ----
 * Particular user model
 * ----
 * @package    Avrelia
 * @author     Avrelia.com
 * @copyright  Copyright (c) 2010, Avrelia.com
 * @license    http://framework.avrelia.com/license
 * @link       http://framework.avrelia.com
 * @since      Version 0.80
 * @since      2012-04-15
 */

class userModel
{
	private $loggedin;
	private $id;

	private $Data;

	/**
	 * Init current user
	 */
	public function __construct()
	{
		$this->reload(false);
	}
	//-

	/**
	 * Will reload this user's data
	 * --
	 * @param	boolean	$reloadSession	Should session information be reloaded too?
	 * 									Useful after doing profile updates.
	 * --
	 * @return	void
	 */
	public function reload($reloadSession=true)
	{
		if ($reloadSession) {
			cSession::Reload();
		}

		$this->loggedin = cSession::IsLoggedin();
		$this->id       = cSession::AsArray('id');

		if ($this->loggedin) {
			# Get details
			$Details = cDatabase::Find('users_details', array('user_id' => $this->id));

			if ($Details->count() === 1) {
				$this->Data = array_merge(cSession::AsArray(), $Details->asArray(0));
			}
		}
	}
	//-

	public function __get($name)
	{
		return isset($this->Data[$name]) ? $this->Data[$name] : false;
	}
	//-
}
//--
