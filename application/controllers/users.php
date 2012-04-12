<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class usersController
{
	/**
	 * Will register the user
	 * --
	 * @return	void
	 */
	public function register()
	{
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
	 * @return	void
	 */
	public function activate()
	{
		uMessage::Add('WAR', l('USER_ACCOUNT_ACTIVATE_INVALID_KEY'), __FILE__);
		View::Get('_assets/generic_message', array(
			'title'         => l('USER_ACCOUNT_ACTIVATE_WAIT'),
			'description'   => l('USER_ACCOUNT_ACTIVATE_WAIT_DESCRIPTION'),
			'message'       => l('USER_ACCOUNT_ACTIVATE_WAIT_MESSAGE'),
			'button_1'      => cHTML::Link(l('CANCEL'), url(), 'class="left plain button"'),
			'button_2'      => cHTML::Link(l('OK_SEND_IT'), url(), 'class="right button"'),
		));
		return;

		//uMessage::Add('OK', l('ACCOUNT_ACTIVATE_MESSAGE'), __FILE__);
        //
		//cHTML::AddHeader('<script>
		//	var timezoneArray = '.uJSON::Encode(timezoneArray()).'
		//</script>', 'timezoneArray');
		//View::Get('users/activate');
	}
	//-

	/**
	 * Will login the user if possible
	 * --
	 * @return	void
	 */
	public function login()
	{
		# We have post, try to login...
		if (Input::HasPost()) {
			cSession::Login(Input::Post('username'), Input::Post('password'));

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
		# Get Forgot Password's Region
		View::Get('users/forgot_password');
	}
	//-
}
//--
