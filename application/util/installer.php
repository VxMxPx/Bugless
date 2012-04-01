<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class uInstaller
{
	/**
	 * Will setup system, so that it will be fully functional.
	 * Before proceed, well check if "installed" value exists in database
	 * and if "installed" in settings is set to "false".
	 * After that we'll check if all folders (which needs to be),
	 * ar writeable.
	 * If all that is true, we'll create all tables which we need,
	 * and insert all default settings.
	 * If that goes well, we have fully functional system.
	 *
	 * Return:
	 * 	-1		Installed, redirect
	 * 	false	Error, display
	 * 	true	Success
	 * --
	 * @return	mixed
	 */
	public static function Run()
	{
		# See if database plug is enabled
		if (!Plug::Has('database')) {
			uMessage::Log('WAR', 'Database plug isn\'t enabled.', __LINE__, __FILE__);
			return false;
		}

		# Check settings in config
		if (Cfg::Get('bugless/installed', true)) {
			uMessage::Log('INF', "System is already installed. If you want to reinstall it, change the `installed` setting in `application/config/bugless.php`.", __LINE__, __FILE__);
			return -1;
		}

		# Try to get installed value from settings table
		$Setting = cDatabase::Read(
							'settings',
							array('project_id' => 0, 'key' => 'installed'));

		if ($Setting->succeed()) {
			uMessage::Log('INF', "System is already installed. Found the record in database.", __LINE__, __FILE__);
			return -1;
		}

		# All folder writable?
		foreach(array('public', 'database', 'log') as $folder) {
			if (is_dir(ds(APPPATH.'/'.$folder))) {
				if (!FileSystem::IsWritable(ds(APPPATH.'/'.$folder))) {
					uMessage::Log('WAR', 'Folder `application/'.$folder.'` must be writable.', __LINE__, __FILE__);
					return false;
				}
			}
		}

		# Read database sql
		$sql = FileSystem::Read(ds(APPPATH.'/config/database.sql'));

		# Valid file?
		if (substr($sql, 0, 10) !== '-- Bugless') {
			Log::Add('WAR', "SQL file is invalid: `application/config/database.sql`.", __LINE__, __FILE__);
			return false;
		}

		# Execute it!
		$Result = cDatabase::Query($sql);

		# Finished
		return $Result->succeed();
	}
	//-
}
//-
