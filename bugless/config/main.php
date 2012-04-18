<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

$AvreliaConfig = array
(
	# System Configuration
	'system' => array
	(
		#
		'routes' => array
		(
			/*  ****************************************************** *
			 *          Default actions
			 *  **************************************  */

			# We'll make everything ready here
			'before'    => 'application->before()',

			# If there's no parameteres set in our URL, this will be called.
			0           => 'projects->dashboard()',

			# The 404 route.
			# If not provided / not found, the system will look for 404.php view;
			# if that won't be found either, only 404 plain message will be shown.
			404         => 'application->not_found_404()',

			# Trigger installation process
			'/install/' => 'application->install()',

			/*  ****************************************************** *
			 *          Users
			 *  **************************************  */

			# Login
			'/login/i'                              => 'users->login()',

			# Logout
			'/logout/i'                             => 'users->logout()',

			# Register
			'/register/i'                           => 'users->register()',

			# Resend activation mail
			'/activate\/resend\/([0-9]*)/i'         => 'users->activate_resend(%1)',

			# Activate
			'/activate\/([a-zA-Z0-9]*)\/([0-9]*)/i' => 'users->activate(%1, %2)',

			# Forgot password
			'/forgot_password/i'                    => 'users->forgot_password()',

			# Match home/$method/$Parameters
			# Controller and method can consist only of: a-z 0-9 _
			# Parameter can be any length and contain (almost) any character.
			# '/([a-z0-9_-]*)\/?([a-zA-Z0-9\/!=\-+_.,;?]*)/' => 'home->%1(%2)',

			# Match $controller/$method/$Parametera
			# Controller and method can consist only of: a-z 0-9 _
			# Parameter can be any length and contain (almost) any character.
			# '/([a-z0-9_-]*)\/?([a-z0-9_-]*)\/?([a-zA-Z0-9\/!=\-+_.,;?]*)/' => '%1->%2(%3)',
		),
	),

	# Plugs settings.
	'plug' => array
	(
		'auto_load'  => array('debug'),

		# Which plugs do we want to use in our application.
		'enabled'    => array('html', 'jquery', 'form', 'database', 'session', 'validate', 'mail', 'debug'),
	),

	# Cookies settings.
	'cookie' => array
	(
		# Cookie prefix (can be empty if you want so).
		'prefix' => 'bugless_',
	),

	# Log settings.
	'log' => array
	(
		# Is log enabled (writing to file)?
		# Please before you enabled log, make sure your log folder is writeable.
		'enabled' => false,
	),
);
