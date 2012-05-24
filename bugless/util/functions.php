<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

/**
 * Get All Settings From Database and put them to main Cfg
 * --
 * @return	void
 */
function getDatabaseSettings()
{
	$Settings = cDatabase::Find('settings');

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
		$continent = isset($Value[0]) ? $Value[0] : null;
		$country   = isset($Value[1]) ? $Value[1] : null;
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
			View::Get('_assets/master')->asMaster();
			View::Get('application/not_allowed')->asRegion('main');
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
 * @param	string	$key		Can be property or: property|another,
 * 								in case first doesn't exists, second will be used.
 * @param	boolean	$return
 * --
 * @return	string
 */
function userInfo($key, $return=false)
{
	$User = Model::Get('user');
	$return = null;

	if (strpos($key, '|') !== false) {
		$key = vString::ExplodeTrim('|', $key);
	}
	else {
		$key = array($key);
	}

	foreach ($key as $item) {
		$return = $User->{$item};
		if ($return) {
			break;
		}
	}

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
	cHTML::AddFooter('<script>Bugless.run(\''.ucfirst(strtolower($name)).'\');</script>', 'jsClass');
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
	$tags = vString::ExplodeTrim(',', $tags);
	$colors = Cfg::Get('bugless/colors');
	shuffle($colors);
	$pos    = 0; # Position in colors array
	$max    = count($colors)-1; # Max position in color array
	$result = array();

	foreach ($tags as $tag) {

		if (empty($tag)) continue;

		if (strlen($tag) > 40) {
			$tag = substr($tag,0,40);
		}

		$result[] = array(
			'tag'   => $tag,
			'color' => $colors[$pos]
		);

		$pos = $pos < $max ? $pos+1 : 0;
	}

	return $result;
}
//-
