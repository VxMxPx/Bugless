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
