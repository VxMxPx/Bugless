<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class usersController
{
	public function __construct()
	{
		jsController('users');
	}
	//-

	/**
	 * Display or save user's profile!
	 * --
	 * @param	boolean	$page	The current page
	 * --
	 * @return	void
	 */
	public function profile($page=false)
	{
		# Must be able to access profile of course
		if (!allow('profile', true)) { return false; }

		if (Input::HasPost()) {
			if (Model::Get('users')->update(Input::Post(), userInfo('id'))) {
				uMessage::Add('OK', l('YOUR_PROFILE_WAS_UPDATED'), __FILE__);
				Model::Get('user')->reload();
				date_default_timezone_set(userInfo('timezone'));
			}
			else {
				uMessage::Add('WAR', l('PROFILE_UPDATE_FAILED'), __FILE__);
			}
		}

		if ($page === 'first') {
			uMessage::Add('OK', l('ACCOUNT_ACTIVATE_MESSAGE'), __FILE__);
		}

		# Set timezone
		$timezone = userInfo('timezone');
		if ($timezone && strpos($timezone, '/') !== false) {
			$timezone = explode('/', $timezone, 2);
		}
		else {
			$timezone = array();
			$timezone[0] = 'UTC';
			$timezone[1] = false;
		}

		cHTML::AddHeader('<script>
			var timezoneArray = '.uJSON::Encode(timezoneArray()).',
				defaultsItems = '.uJSON::Encode($timezone).';
		</script>', 'timezoneArray');

		View::Get('_assets/master')->asMaster();
		View::Get('users/profile', array(
			'first'    => $page === 'first' ? true : false,
			'Defaults' => array(
				'full_name' => userInfo('full_name'),
				'language' => userInfo('language'),
			)
		))->asRegion('main');
	}
	//-

	/**
	 * Display or save user's profile - login settings
	 * --
	 * @return void
	 */
	public function profile_login()
	{
		# Must be able to access profile
		if (!allow('profile', true)) { return false; }

		View::Get('_assets/master')->asMaster();
		View::Get('users/profile_login', array(
		))->asRegion('main');
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
		# If login isn't allowed, then actiovation isn't either!
		if (!allow('login', true)) { return false; }

		if (Model::Get('users')->activate($key, (int)$userId))
		{
			HTTP::Redirect(url('profile/first'));
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
