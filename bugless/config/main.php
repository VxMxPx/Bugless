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
			'<before>'    => 'application->before()',

			# If there's no parameteres set in our URL, this will be called.
			'<index>'     => 'dashboard->index()',

			# The 404 route.
			# If not provided / not found, the system will look for 404.php view;
			# if that won't be found either, only 404 plain message will be shown.
			'<404>'       => 'application->not_found_404()',

			# Trigger installation process
			'install'     => 'application->install()',

			/*  ****************************************************** *
			 *          Users
			 *  **************************************  */

			# Login
			'login'                        => 'users->login()',

			# Logout
			'logout'                       => 'users->logout()',

			# Register
			'register'                     => 'users->register()',

			# Resend activation mail
			'activate/resend/<09>'         => 'users->activate_resend(%3)',

			# Activate
			'activate/<aZ09>/<09>'         => 'users->activate(%2, %3)',

			# Profile login settings
			'profile/edit_login'           => 'users->profile_login()',

			# Profile
			'profile/?<aZ_>'               => 'users->profile(%2)',

			# Forgot password
			'forgot_password'              => 'users->forgot_password()',
		),


		# Turn debug off
		'debug'            => false,
	),

	# Plugs settings.
	'plug' => array
	(
		'auto_load'  => array(),

		# Which plugs do we want to use in our application.
		'enabled'    => array('html', 'jquery', 'form', 'database', 'session', 'validate', 'mail'),
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
