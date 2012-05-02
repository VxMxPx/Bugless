<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class usersController
{
	public function __construct()
	{
		jsController('users');
	}
	//-

	/**
	 * Will register the user
	 * --
	 * @return	void
	 */
	public function register()
	{
		if (!allow('register', true)) { return false; }

		if (Input::HasPost()) {
			if (Model::Get('users')->register(Input::Post())) {
				uMessage::Add('OK', l('USER_ACCOUNT_SUCCESSFULLY_CREATED'), __FILE__);
				View::Get('_assets/generic_message', array(
					'title'         => l('USER_ACCOUNT_SUCCESSFULLY_CREATED'),
					'message'       => l('USER_ACCOUNT_CHECK_EMAIL', '<strong>'.Input::Post('email').'</strong>'),
					'button_1'      => cHTML::Link(l('OK'), url(), 'class="right button"'),
				));
				return;
			}
			else {
				if (!uMessage::Exists()) {
					uMessage::Add('WAR', l('FAILED_TO_CREATE_ACCOUNT'), __FILE__);
				}
			}
		}
		
		View::Get('users/register');
	}
	//-

	/**
	 * Will activate user's account
	 * --
	 * @param	string	$key
	 * @param	integer	$userId
	 * --
	 * @return	void
	 */
	public function activate($key, $userId)
	{
		# If we can't login ...
		if (!allow('login', true)) { return false; }

		if (Model::Get('users')->activate($key, (int)$userId))
		{
			uMessage::Add('OK', l('ACCOUNT_ACTIVATE_MESSAGE'), __FILE__);

			cHTML::AddHeader('<script>
				var timezoneArray = '.uJSON::Encode(timezoneArray()).'
			</script>', 'timezoneArray');
			View::Get('users/activate');
		}
		else {
			uMessage::Add('WAR', l('USER_ACCOUNT_ACTIVATE_INVALID_KEY'), __FILE__);

			View::Get('_assets/generic_message', array(
				'title'         => l('USER_ACCOUNT_ACTIVATE_WAIT'),
				'description'   => l('USER_ACCOUNT_ACTIVATE_WAIT_DESCRIPTION'),
				'message'       => l('USER_ACCOUNT_ACTIVATE_WAIT_MESSAGE'),
				'button_1'      => cHTML::Link(l('CANCEL'), url(), 'class="left plain button"'),
				'button_2'      => cHTML::Link(l('OK_SEND_IT'), url('activate/resend/'.(int)$userId), 'class="right button"'),
			));
		}
	}
	//-

	/**
	 * Will resend activation e-mail
	 * --
	 * @param	integer	$userId
	 * --
	 * @return	void
	 */
	public function activate_resend($userId)
	{
		# If we can't login ...
		if (!allow('login', true)) { return false; }

		if (Model::Get('users')->activate_resend((int)$userId))
		{
			View::Get('_assets/generic_message', array(
				'title'         => l('ACCOUNT_RESEND_CHECK_MAIL'),
				'message'       => l('ACCOUNT_RESEND_MESSAGE'),
				'button_1'      => cHTML::Link(l('OK'), url(), 'class="right button"'),
			));
			return;
		}
		else {
			View::Get('_assets/generic_message', array(
				'title'         => l('ACCOUNT_RESEND_FAILED'),
				'message'       => l('ACCOUNT_RESEND_FAILED_MESSAGE', lu('register', 'login', 'forgot_password')),
				'button_1'      => cHTML::Link(l('OK'), url(), 'class="right button"'),
			));
			return;
		}
	}
	//-

	/**
	 * Will login the user if possible
	 * --
	 * @return	void
	 */
	public function login()
	{
		# If we can't login ...
		if (!allow('login', true)) { return false; }

		# We have post, try to login...
		if (Input::HasPost()) {
			cSession::Login(Input::Post('email'), Input::Post('password'));

			if (cSession::IsLoggedin()) {
				HTTP::Redirect(url());
			}
			else {
				uMessage::Add('WAR', l('INVALID_USERNAME_OR_PASSWORD'), __FILE__);
			}
		}

		# Get Login View
		View::Get('users/login');
	}
	//-

	/**
	 * Will logout the user
	 * --
	 * @return	void
	 */
	public function logout()
	{
		if (cSession::IsLoggedin()) {
			cSession::Logout();
		}

		HTTP::Redirect(url());
	}
	//-

	/**
	 * Will trigger the password recovery process.
	 * ---
	 * @return void
	 */
	public function forgot_password()
	{
		# If we can't login ...
		if (!allow('login', true)) { return false; }

		# Get Forgot Password's Region
		View::Get('users/forgot_password');
	}
	//-
}
//--
