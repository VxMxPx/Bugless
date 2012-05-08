<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

/**
 * Get All Settings From Database and put them to main Cfg
 * --
 * @return	void
 */
function getDatabaseSettings()
{
	$Settings = cDatabase::Find('settings', array('project_id' => 0));

	if ($Settings->succeed()) {
		$Settings = $Settings->asArray();

		$Final = array();
		if (is_array($Settings)) {
			foreach ($Settings as $Opt) {
				$Final[$Opt['key']] = $Opt['value'];
			}
		}

		Cfg::Append(array('bugless' => $Final));
	}
}
//-

/**
 * Get list (array) of available languages
 * --
 * @return	array
 */
function getLanguagesList()
{
	return array('en' => l('ENGLISH'));
}
//-

/**
 * Will Get All Continents
 * --
 * @return	array
 */
function timezoneGetContinents()
{
	$List = timezone_identifiers_list();
	$NewList = array();

	foreach ($List as $value) {
		$value = vString::Split('/', $value, 0, 2);
		$NewList[$value] = str_replace('_', ' ', $value);
	}

	return $NewList;
}
//-

/**
 * Get timezones in [Continent][Country] array
 * --
 * @return	array
 */
function timezoneArray()
{
	$List = timezone_identifiers_list();
	$NewList = array();

	foreach ($List as $value) {
		$Value     = vString::ExplodeTrim('/', $value, 2);
		$continent = $Value[0];
		$country   = $Value[1];
		$NewList[$continent][$country] = str_replace('_', ' ', $country);
	}

	return $NewList;
}
//-

/**
 * Check if user is logged in or not.
 * --
 * @return	boolean
 */
function loggedin()
{
	return cSession::IsLoggedin();
}
//-

/**
 * Check if particular action is allowed.
 * --
 * @param	string	$what
 * @param	boolean	$message	If set to true, them message will be rendered,
 * 								informing user, about the access restriction.
 * --
 * @return	boolean
 */
function allow($what, $message=false)
{
	if (!Model::Get('permissions')->canAccess($what)) {
		if ($message) {
			View::Get('application/not_allowed');
		}
		return false;
	}
	else {
		return true;
	}
}
//-

/**
 * Get particular information about user
 * --
 * @param	string	$key
 * @param	boolean	$return
 * --
 * @return	string
 */
function userInfo($key, $return=false)
{
	$return = Model::Get('user')->{$key};

	if ($return) {
		return $return;
	}
	else {
		echo $return;
	}
}
//-

/**
 * Will execute particular javaScript controller
 * --
 * @param	string	$name	The name of controller we wanna execute
 * --
 * @return	void
 */
function jsController($name)
{
	cHTML::AddFooter('<script>Bugless.run(\''.$name.'\');</script>', 'jsClass');
}
//-

/**
 * Take list of tags, and clean it, - check for tags which exits,
 * check for too long tags names and make them shorter, 
 * return list of valid tags with color code included.
 * --
 * @param	string	$tags	List of tags: tag,another,third
 * @param	string	$type	Type of request: projects / bugs
 * --
 * @return	array
 */
function tagsCleanup($tags, $type)
{
	return $type;
}
//-