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

		# Add additional fields
		$Data['id']     = null;
		$Data['active'] = false;
		$Data['activation_key'] = vString::Random(40, 'aA1');
		$Data['uname'] = $Data['email'];
		$Data['password'] = $Data['pwd'];

		unset($Data['email'], $Data['pwd'], $Data['pwd_again']);

		# Insert new user
		$Record = cDatabase::Create($Data, 'users');

		# Was this successful?
		if ($Record->succeed())
		{
			$link = url("activate/{$Data['activation_key']}");

			$mailContent = Cfg::Get('bugless/mail_registration');
			$mailContent = str_replace(array('{{link}}', '{{site_title}}'), array($link, Cfg::Get('bugless/site_title')), $mailContent);

			$Mail = new cMail();
			$Mail
				->from(Cfg::Get('bugless/mail_from'))
				->to($Data['uname'])
				->subject(l('EMAIL_CONFORMATION_SUBJECT', Cfg::Get('bugless/site_title')))
				->message(false, $mailContent)
				->send();

			# Last insertion
			return
			cDatabase::Create(array(
				'user_id'    => $Record->insertId(),
				'created_on' => gmdate('YmdHis'),
				'updated_on' => gmdate('YmdHis'),
				'timezone'   => 'GMT',
				'language'   => 'en'
			), 'users_details')->succeed();
		}
	}
	//-
}
//--
