<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

$AvreliaConfig['bugless'] = array
(
	# When Bugless is installed, a value "installed" is created in database's settings,
	# containing version of system and date of installation.
	# System will always on boot (when reading all settings anyway) check if that value is there,
	# and if it's not, it will permit running installer.
	# On other hand, if someone will wanted to run installer, and that value isn't set,
	# he won't be able (preventing silly, dangerous fatal rewrites of database).
	#
	# However, if you'd like to be sure, you can set this value here to true, making it impossible to run installer.
	'installed' => false,

	# List of colors used for tagging labels
	'colors'    => array(
		'#cc3333', '#cc6633', '#339933', '#006666', '#333399', '#663399', 
		'#993399', '#6699cc', '#336633', '#990066', '#333333', '#ff9900',
	),
);
