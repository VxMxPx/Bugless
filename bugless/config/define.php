<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

/**
 * Avrelia
 * ----
 * Define All Paths. This is used by system (default) and CLI
 * ----
 * @package    Avrelia
 * @author     Avrelia.com
 * @copyright  Copyright (c) 2009, Avrelia.com
 * @license    http://avrelia.com/license
 * @link       http://avrelia.com
 * @since      Version 0.80
 * @since      Thu Apr 14 19:17:09 2011
 */

# Do we have local version of this file?
$localDefine = realpath(dirname(__FILE__).'/define.local.php');
if (file_exists($localDefine)) {
	include $localDefine;
}

# Try To get Path Automatically, You should change this in case of problems...
if (!defined('APPPATH')) define('APPPATH', realpath(dirname(__FILE__).'/../../bugless'));
if (!defined('PUBPATH')) define('PUBPATH', realpath(dirname(__FILE__).'/../../profile/public'));
if (!defined('DATPATH')) define('DATPATH', realpath(PUBPATH.'/../database'));
if (!defined('SYSPATH')) define('SYSPATH', realpath(APPPATH.'/system'));

# Bugless things
# Please note, version numbers are real, regular decimals, meaning:
# 	0.90 === 0.9 && 0.9 > 0.85
define('BUGLESS',			true);
define('BUGLESS_VERSION',	0.90);
