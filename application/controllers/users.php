<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class usersController
{
	/**
	 * Will register the user
	 * ---
	 * @return void
	 */
	public function register()
	{
		View::Get('users/register');
	}
	//-

	/**
	 * Will login the user if possible
	 * ---
	 * @return void
	 */
	public function login()
	{
		# Get Login View
		View::Get('users/login');
	}
	//-

	/**
	 * Will logout the user
	 * ---
	 * @return void
	 *
	 */
	public function logout()
	{
		return;
	}
	//-

	/**
	 * Will trigger the password recovery process.
	 * ---
	 * @return void
	 */
	public function forgot_password()
	{
		# Get Forgot Password's Region
		View::Get('users/forgot_password');
	}
	//-
}
//--
