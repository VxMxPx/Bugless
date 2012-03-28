<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

$AvreliaConfig = array
(
	# System Configuration
	'system' => array
	(
		#
		'routes'           => array
		(
			/*  ****************************************************** *
			 *          Projects
			 *  **************************************  */

			# If there's no parameteres set in our URL, this will be called.
			0   => 'projects->list()',

			# The 404 route.
			# If not provided / not found, the system will look for 404.php view;
			# if that won't be found either, only 404 plain message will be shown.
			404 => 'application->not_found_404()',

			/*  ****************************************************** *
			 *          Users
			 *  **************************************  */

			# Login
			'/login/i'           => 'users->login()',

			# Logout
			'/logout/i'          => 'users->logout()',

			# Register
			'/register/i'		 => 'users->register()',

			# Forgot password
			'/forgot_password/i' => 'users->forgot_password()',

			# Match home/$method/$Parameters
			# Controller and method can consist only of: a-z 0-9 _
			# Parameter can be any length and contain (almost) any character.
			# '/([a-z0-9_-]*)\/?([a-zA-Z0-9\/!=\-+_.,;?]*)/' => 'home->%1(%2)',

			# Match $controller/$method/$Parametera
			# Controller and method can consist only of: a-z 0-9 _
			# Parameter can be any length and contain (almost) any character.
			# '/([a-z0-9_-]*)\/?([a-z0-9_-]*)\/?([a-zA-Z0-9\/!=\-+_.,;?]*)/' => '%1->%2(%3)',
		),

		# Languages
		# List of default languages, this is used, if you're loading language with % in filename.
		# For example: my_language.%.lng, and for loading plug's default language.
		# The languages will be loaded in order provided bellow, meaning,
		# first 0, then if 0 can't be found, load 1, etc...
		'languages'        => array('en'),

		# Full URL (domain name) (http://example.com)
		'full_url'         => '',

		# Is application offline?
		'offline'           => false,

		# If application is offline, what kind of message do we want to send to our users?
		# This can be any string, or view:view_name, will be loaded from application/views folder.
		'offline_message'   => 'view:offline',

		# Default System Timezone.
		'timezone'         => 'GMT',

		# While URI cleanup (this isn't used in routes, as they have their own filter)
		# It's used with Input::Get();
		'input_get_filter' => '/[^a-zA-Z0-9\/!=\-+_.,;?]/',

		# Turn debug on?
		'debug'            => true,

		# Allow redirects? Useful for debuging.
		'allow_redirects'  => true,

		# Cache directory
		'cache_dir'        => APPPATH . '/database/cache',

		# The files / folder which we need to ignore when copying...
		'ignore_on_copy'   => array('.svn'),
	),

	# Plugs settings.
	'plug' => array
	(
		# Which plugs do we want to use in our application.
		'enabled'    => array('html', 'jquery'),

		# Which plugs do we want to autoload at the begining.
		# Plug must be on enabled list, in order to be auto loaded.
		'auto_load'  => array(),

		# Plugs default public directory
		'public_dir' => 'plugs',

		# Plugs force refresh public, this mean, on every request we drop
		# public folder for plugs and copy it again. This is useful only
		# for plug development. Set it to false otherwise!
		'debug'      => false,
	),

	# Cookies settings.
	'cookie' => array
	(
		# Enter domain name (example.com).
		'domain' => '',

		# Cookie prefix (can be empty if you want so).
		'prefix' => 'avrelia_',

		# Default timeout (seconds).
		'timeout' => 86400,
	),

	# Log settings.
	'log' => array
	(
		# Is log enabled (writing to file)?
		# Please before you enabled log, make sure your log folder is writeable.
		'enabled' => false,

		# Full log path (full path + filename) - where all log messages will be sabed.
		'path'    => APPPATH . '/log/' . date('Y') . '/' . date('m') . '/' . date('d') . '.log',

		# Log types. Select which type of messages should be saved. INF and OK isn't recomended to be saved.
		'types'   => array
		(
			'ERR' => true,
			'WAR' => true,
			'INF' => false,
			'OK'  => false,
		),

		# If set to true, every log message will be saved individually -
		# if set to false, all messages will be saved at the end of script execution.
		'write_individual'   => true,

		# Write all on fatal error. Set to true, special file will be created,
		# which will contain only one fatal event, with whole session informations (it will include ERR, WAR, INF).
		'write_all_on_fatal' => true,

		# Filename for fatal error (only if you set 'write_all_on_fatal' to true).
		'fatal_path'         => APPPATH . '/log/' . date('Y') . '/' . date('m') . '/fatal/' . date('Y-m-d__H-i-s') . '.log',
	),
);
