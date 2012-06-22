<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

/**
 * Avrelia
 * ----
 * Users Settings, Registration, etc...
 * ----
 * @package    Avrelia
 * @author     Avrelia.com
 * @copyright  Copyright (c) 2010, Avrelia.com
 * @license    http://framework.avrelia.com/license
 * @link       http://framework.avrelia.com
 * @since      Version 0.80
 * @since      2012-04-02
 */
class usersModel
{
	/**
	 * Register new user's account
	 * --
	 * @param	array	$Data
	 * --
	 * @return	boolean
	 */
	public function register($Data)
	{
		# All must be string and exists
		vArray::Filter($Data, array(
			'email' 	=> 'string|',
			'pwd'   	=> 'string|',
			'pwd_again' => 'string|',
		));

		# Validate
		cValidate::Add($Data['email'], l('EMAIL'))->hasValue()->isEmail();
		cValidate::Add($Data['pwd'],   l('PASSWORD'))->hasValue()->isLength(3, 40);

		# Have password match?
		if ($Data['pwd'] !== $Data['pwd_again']) {
			uMessage::Add('WAR', l('PASSWORD_DOESNT_MATCH'), __FILE__);
			return false;
		}

		if (!cValidate::IsValid()) {
			return false;
		}

		# New check if we have user with such e-mail already in our database
		if (cDatabase::Find('users', array('uname' => $Data['email']))->count() > 0) {
			uMessage::Add('WAR', l('EMAIL_ALREADY_IN_USE', $Data['email']), __FILE__);
			return false;
		}

		# Add additional fields
		$Data['id']     = null;
		$Data['active'] = false;
		$Data['activation_key'] = vString::Random(40, 'aA1');
		$Data['uname']          = $Data['email'];
		$Data['password']       = vString::Hash($Data['pwd'], false, true);

		unset($Data['email'], $Data['pwd'], $Data['pwd_again']);

		# Insert new user
		$Record = cDatabase::Create($Data, 'users');

		# Was this successful?
		if ($Record->succeed())
		{
			$this->send_activation_email($Data['activation_key'], $Record->insertedId(), $Data['uname']);

			# Last insertion
			return
			cDatabase::Create(array(
				'user_id'    => $Record->insertedId(),
				'created_on' => gmdate('YmdHis'),
				'updated_on' => gmdate('YmdHis'),
				'timezone'   => 'GMT',
				'language'   => 'en'
			), 'users_details')->succeed();
		}
	}
	//-

	/**
	 * Will change settings for particular user
	 * --
	 * @param	array	$Data
	 * @param	integer	$id
	 * --
	 * @return	boolean
	 */
	public function update($Data, $id)
	{
		vArray::Filter($Data, array(
			'full_name' => 'string|',
			'continent' => 'string|',
			'country'   => 'string|',
			'language'  => 'string|',
		));

		$Data['timezone'] = trim($Data['continent'] . '/' . $Data['country'], '/');
		unset($Data['continent'], $Data['country']);

		# Cleanup
		$Data['full_name']  = htmlentities( substr( strip_tags($Data['full_name']), 0, 100),  ENT_COMPAT | ENT_HTML401, 'UTF-8' );
		$Data['timezone']   = vString::Clean($Data['timezone'], 255, 'aA1cs', '/_-');
		$Data['language']   = vString::Clean($Data['language'], 4, 'a');
		$Data['updated_on'] = gmdate('YmdHis');

		# Update it
		return cDatabase::Update($Data, 'users_details', array('user_id' => $id))->succeed();
	}
	//-

	/**
	 * Will activate user's account if possible
	 * --
	 * @param	string	$key
	 * @param	integer	$userId
	 * --
	 * @return	boolean
	 */
	public function activate($key, $userId)
	{
		$key    = vString::Clean($key, 40, 'aA1');
		$userId = (int) $userId;

		if (strlen(trim($key)) < 40) {
			Log::Add("Key must be exactly 40 characters long.", 'ERR');
			return false;
		}

		if ($userId < 1) {
			Log::Add("User's id must be valid positive number `{$userId}`.", 'WAR');
			return false;
		}

		$User = cDatabase::Find('users', array('activation_key' => $key, 'id' => $userId, 'active' => 0));

		if ($User->count() === 1)
		{
			cDatabase::Update(array(
				'activation_key' => 0,
				'active'         => 1
			), 'users', array('id' => $userId));


			return cSession::LoginId($userId);
		}
		else {
			return false;
		}
	}
	//-

	/**
	 * Will regenerate and resend activation key
	 * --
	 * @param	integer	$userId
	 * --
	 * @return	boolean
	 */
	public function activate_resend($userId)
	{
		# First check if we have appropriate user
		$userId = (int) $userId;

		if ($userId < 1) {
			Log::Add("User's id must be valid positive number `{$userId}`.", 'WAR');
			return false;
		}

		$User = cDatabase::Find('users', array('activation_key !=' => 0, 'id' => $userId, 'active' => 0));

		if ($User->count() === 1)
		{
			$User = $User->asArray(0);
			$activationKey = vString::Random(40, 'aA1');

			if ($this->send_activation_email($activationKey, $User['id'], $User['uname'])) {
				return
					cDatabase::Update(array(
						'activation_key' => $activationKey,
					), 'users', array('id' => $userId));
			}
		}

		return false;
	}
	//-

	/**
	 * Will send activation key to particular user
	 * --
	 * @param	string	$key
	 * @param	integer	$userId
	 * --
	 * @return	boolean
	 */
	private function send_activation_email($key, $userId, $userEmail)
	{
		$link = url("activate/{$key}/".$userId);

		$mailContent = Cfg::Get('bugless/mail_registration');
		$mailContent = str_replace(array('{{link}}', '{{site_title}}'), array($link, Cfg::Get('bugless/project_name')), $mailContent);

		$Mail = new cMail();

		return $Mail
				->from(Cfg::Get('bugless/mail_from'))
				->to($userEmail)
				->subject(l('EMAIL_CONFORMATION_SUBJECT', Cfg::Get('bugless/project_name')))
				->message(false, $mailContent)
				->send();
	}
	//-
}
//--
