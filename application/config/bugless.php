<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

$Config['bugless'] = array
(
	# When bugless is installed, a value "installed" is created in database's settings,
	# containig version of system and date of installation.
	# System will always on boot (when reading all settings anyway) check if that value is there,
	# and if it's not, it will run installer.
	# On other hand, if someone will wanted to run installer, and that value is set,
	# he won't be able (preventing silly, dangerious fatal rewrites of database).
	#
	# However, if you're paranoid, you can set this value here also, makind it almost
	# like a harware setting - as these config files here can be edit only manually.
	'installed' => false,
);
