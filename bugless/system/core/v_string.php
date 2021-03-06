<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

/**
 * Avrelia
 * ----
 * String Manipulation
 * ----
 * @package    Avrelia
 * @author     Avrelia.com
 * @copyright  Copyright (c) 2010, Avrelia.com
 * @license    http://framework.avrelia.com/license
 * @link       http://framework.avrelia.com
 * @since      Version 0.80
 * @since      2012-03-20
 */

class vString
{
	/**
	 * This function works similar as native crypt in PHP.
	 *
	 * You can provide salt to it, if you don't the salt will be auto-generated.
	 * When comparing, you must use hash itself as salt. So:
	 * if ($input === vString::Hash($input, $hashedPassword)) ...
	 *
	 * The output will be slightly modified sha1: ah10salt.hash:
	 * a(vrelia)
	 * h(ash)
	 * 1(version)
	 * 0(method, currently only: sha1)
	 * salt
	 * .
	 * hash
	 * --
	 * @param	string	$string
	 * @param	string	$salt
	 * @param	boolean	$attachSalt
	 * --
	 * @return	string
	 */
	public static function Hash($string, $salt=false, $attachSalt=false)
	{
		if ($attachSalt) {
			if (!$salt) {
				$salt = str_replace('.', '+', md5(self::Random(12)));
			}
			else {
				# Check if we have anything meaningful
				if (substr($salt, 0, 4) === 'ah10') {
					$salt = substr($salt, 4);
					$salt = explode('.', $salt, 2);
					$salt = $salt[0];
				}
				else {
					$salt = str_replace('.', '+', $salt);
				}
			}

			return 'ah10' . $salt . '.' . sha1( sha1 ($string) . sha1 ($salt) );
		}
		else {
			if ($salt) {
				return sha1( sha1 ($string) . sha1 ($salt) );
			}
			else {
				return sha1( sha1 ($string) );
			}
		}
	}
	//-

	/**
	 * Convert signs (like €, $, #) to regular words.
	 * --
	 * @param	string	$string
	 * --
	 * @return	string
	 */
	public static function SymbolsToWords($string)
	{
		$signs = array(
			'’' => 'apostrophe', "'" => 'apostrophe', '[' => 'left square bracket',
			']' => 'right square bracket', '(' => 'left bracket', ')' => 'right bracket',
			'{' => 'left curly bracket', '}' => 'right curly bracket', ':' => 'colon',
			',' => 'comma', '‒' => 'dash', '–' => 'dash', '—' => 'dash', '―' => 'dash',
			'…' => 'ellipsis', '...' => 'ellipsis', '. . .' => 'ellipsis', '!' => 'exclamation',
			'.' => 'period', '«' => 'left guillemet', '»' => 'right guillemet', '-' => 'minus',
			'?' => 'question', '‘' => 'left quote', '’' => 'right quote', '“' => 'left quote',
			'”' => 'right quote', '“' => 'left quote', '"' => 'quote', ';' => 'semicolon',
			'/' => 'slash‌', '⁄' => 'slash‌', ' ' => 'space', '·' => 'interpunct', '&' => 'and',
			'@' => 'at', '*' => 'asterisk', '\\' => 'backslash', '•' => 'bullet', '^' => 'caret',
			'†' => 'dagger', '‡' => 'dagger', '°' => 'degree', '〃' => 'ditto', '¡' => 'inverted exclamation',
			'¿' => 'inverted question', '#' => 'hash', '№' => 'numero', '÷' => 'obelus',
			'º' => 'ordinal', 'ª' => 'ordinal', '%' => 'percent', '‰' => 'per mil',
			'‱' => 'per mil', '¶' => 'pilcrow', '′' => 'prime', '″' => 'prime',
			'‴' => 'prime', '§' => 'section', '+' => 'plus', '=' => 'equal',
			'<' => 'less than', '>' => 'more than', '~' => 'tilde', '_' => 'underscore',
			'|' => 'pipe', '¦' => 'pipe', '©' => 'copyright', '®' => 'registered trademark',
			'℠' => 'service mark', '℗' => 'sound recording copyright', '™' => 'trademark',
			'¤' => 'currency', '⁂' => 'asterism', '⊤' => 'tee', '⊥' => 'up tack', '☞' => 'index',
			'∴' => 'therefore', '∵' => 'because', '‽' => 'interrobang', '◊' => 'lozenge', '※' => 'reference',
			'⁀' => 'tie', '¢' => 'cent', '$' => 'dollar', '€' => 'euro',
		);

		return strtr($string, $signs);
	}
	//-

	/**
	 * Generate Random Code (string)
	 * --
	 * @param	integer	$length
	 * @param	string	$type		a A 1 s => lower case, upper case, numeric, special: ~#$%&()=?*<>-_:.;,+!
	 * --
	 * @return	string
	 */
	public static function Random($length, $type='aA1s')
	{
		$a = 'qwertzuiopasdfghjklyxcvbnm';
		$A = 'QWERTZUIOPASDFGHJKLYXCVBNM';
		$n = '0123456789';
		$s = '~#$%&()=?*<>-_:.;,+!';

		$chars  = '';
		$chars .= ((strpos($type, 'a') !== false) ? $a : '');
		$chars .= ((strpos($type, 'A') !== false) ? $A : '');
		$chars .= ((strpos($type, '1') !== false) ? $n : '');
		$chars .= ((strpos($type, 's') !== false) ? $s : '');

		$i = 1;
		$result = '';

		while ($i <= $length) {
			$result .= $chars{mt_rand(0,strlen($chars)-1)};
			$i++;
		}

		return $result;
	}
	//-

	/**
	 * Standardize line endings
	 * --
	 * @param	string	$input
	 * --
	 * @return	string
	 */
	public static function StandardizeLineEndings($input)
	{
		return preg_replace('/\r{,2}|\n{,2}|\r\n{,2}/ism', "\n", $input);
	}
	//-

	/**
	 * Prepare handle (to be inserted into database)
	 * for example, will convert: "My Title" to "my-title"
	 * --
	 * @param	string	$string			String that WILL be converted to handle
	 * @param	array	$HandlesList	List of existing handles (if we don't wanna duplicates)
	 * --
	 * @return	string
	 */
	public static function ToHandle($string, $HandlesList)
	{
		if (empty($string)) return '';

		if (!is_array($HandlesList)) $HandlesList = array();

		$string = mb_strtolower($string, 'UTF-8');

		# This Will Also normalize chatacters e.g: č => c
		$string = self::Clean($string, 0, 'a A 1 c s', '- _ &');
		$string = str_replace('&', 'and', $string);
		$string = preg_replace('/( |_|-)+/', '-', $string);

		$num = 1;
		$baseString = $string;
		do {
			if ($num == 1) {
				$num++;
			}
			else {
				$string = $baseString . '-' . $num;
				$num++;
			}
		}
		while(in_array($string, $HandlesList));
		return $string;
	}
	//-

	/**
	 * Clean string data
	 * --
	 * @param	string	$string
	 * @param	integer	$length	Define maximum length || 0 - for disable
	 * @param	string	$type	Define type - type.: a A 1 c s (as)  small a-z, up A-Z, nums, costum, spaces
	 * @param	string	$costum	Define costum - example.: ', - + * ! ? #' (use space)
	 * --
	 * @return	string
	 */
	public static function Clean($string, $length=0, $type='aA1cs', $costum='')
	{
		if (empty($string)) return '';

		# Normalize String
		$string = self::Normalize($string);

		$length = (!$length) ? 0       : $length;
		$type   = (!$type)   ? 'aA1cs' : $type;
		$costum = (!$costum) ? ''      : $costum;

		$filter = '/([^';
		$a = 'a-z';
		$A = 'A-Z';
		$n = '0-9';
		$s = '\\ ';
		$c = ''; $special = array('^','.','[',']','$','(',')','|','*','+','-','?','{','\\','/');

		if ($costum != '') {

			# Create Spaces
			$costum = str_replace(' ', '', $costum);
			$costum = preg_replace('/(.)/ism', '\1 ', $costum);

			$costum = explode(' ', $costum);
			foreach ($costum as $val) {
				if (in_array($val, $special)) {
					$c .= '\\' . $val;
				}
				else {
					$c .= $val;
				}
			}
		}

		if (!empty($type)) {
			if (strpos($type, 'a') !== false) { $filter .= $a; }
			if (strpos($type, 'A') !== false) { $filter .= $A; }
			if (strpos($type, '1') !== false) { $filter .= $n; }
			if (strpos($type, 's') !== false) { $filter .= $s; }
			if (strpos($type, 'c') !== false) { $filter .= $c; }
		}

		$filter .= '])/sm';

		$newString = preg_replace($filter, '', $string);

		# Report
		# Log::Add('INF', "Filter: {$filter}\n----------\nInitial: {$string}\n----------\nFinal: {$newString}", __LINE__, __FILE__);

	   if ($length > 0) {
		  $newString = substr($newString,0,$length);
	   }

	   return $newString;
	}
	//-

	/**
	 * Clean string data with the help of regular expression.
	 * Matches are removed!
	 * --
	 * @param	string	$string
	 * @param	string	$regEx		Regular Expression
	 * --
	 * @return string
	 */
	public static function RegExClean($string, $regEx)
	{
		return preg_replace($regEx, '', $string);
	}
	//-

	/**
	 * Normalize to Clean Latin characters, for example convert č => c
	 * --
	 * @param	string	$string
	 * --
	 * @return	string
	 */
	public static function Normalize($string)
	{
		$chars = array(
			'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
			'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C',
			'Ď' => 'D', 'Đ' => 'D', 'Ð' => 'D',
			'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E', 'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E',
			'Ĝ' => 'G', 'Ğ' => 'G', 'Ġ' => 'G', 'Ģ' => 'G',
			'Ĥ' => 'H', 'Ħ' => 'H',
			'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I', 'İ' => 'I', 'Ĳ' => 'Ij', 'Ĵ' => 'J',
			'Ķ' => 'K',
			'Ł' => 'L', 'Ľ' => 'L', 'Ĺ' => 'L', 'Ļ' => 'L', 'Ŀ' => 'L',
			'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N', 'Ņ' => 'N', 'Ŋ' => 'N',
			'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O', 'Œ' => 'Oe',
			'Ŕ' => 'R','Ř' => 'R','Ŗ' => 'R',
			'Ś' => 'S','Š' => 'S','Ş' => 'S','Ŝ' => 'S','Ș' => 'S',
			'Ť' => 'T','Ţ' => 'T','Ŧ' => 'T','Ț' => 'T',
			'Ù' => 'U','Ú' => 'U','Û' => 'U','Ü' => 'Ue','Ū' => 'U','Ů' => 'U','Ű' => 'U','Ŭ' => 'U','Ũ' => 'U','Ų' => 'U',
			'Ŵ' => 'W',
			'Ý' => 'Y','Ŷ' => 'Y','Ÿ' => 'Y','Y' => 'Y',
			'Ź' => 'Z','Ž' => 'Z','Ż' => 'Z',
			'Þ' => 'T',
			'à' => 'a','á' => 'a','â' => 'a','ã' => 'a','ä' => 'ae','å' => 'a','ā' => 'a','ą' => 'a','ă' => 'a','æ' => 'ae',
			'ç' => 'c','ć' => 'c','č' => 'c','ĉ' => 'c','ċ' => 'c',
			'ď' => 'd','đ' => 'd','ð' => 'd',
			'è' => 'e','é' => 'e','ê' => 'e','ë' => 'e','ē' => 'e','ę' => 'e','ě' => 'e','ĕ' => 'e','ė' => 'e',
			'ĝ' => 'g','ğ' => 'g','ġ' => 'g','ģ' => 'g',
			'ĥ' => 'h','ħ' => 'h',
			'ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ī' => 'i','ĩ' => 'i','ĭ' => 'i','į' => 'i','ı' => 'i',
			'ĳ' => 'ij','ĵ' => 'j',
			'ķ' => 'k',
			'ł' => 'l','ľ' => 'l','ĺ' => 'l','ļ' => 'l','ŀ' => 'l',
			'ñ' => 'n','ń' => 'n','ň' => 'n','ņ' => 'n','ŋ' => 'n',
			'ò' => 'o','ó' => 'o','ô' => 'o','õ' => 'o','ö' => 'oe','ø' => 'o','ō' => 'o','ő' => 'o','ŏ' => 'o','œ' => 'oe',
			'ŕ' => 'r','ř' => 'r','ŗ' => 'r',
			'ś' => 's','š' => 's','ş' => 's','ŝ' => 's','ș' => 's',
			'ť' => 't','ţ' => 't','ŧ' => 't','ț' => 't',
			'ù' => 'u','ú' => 'u','û' => 'u','ü' => 'ue','ū' => 'u','ů' => 'u','ű' => 'u','ŭ' => 'u','ũ' => 'u','ų' => 'u',
			'ŵ' => 'w',
			'ý' => 'y','ŷ' => 'y','ÿ' => 'y','y' => 'y',
			'ź' => 'z','ž' => 'z','ż' => 'z',
			'þ' => 't','ß' => 'ss','ſ' => 'ss','ƒ' => 'f','ĸ' => 'k','ŉ' => 'n',
		);
		return strtr($string, $chars);
	}
	//-

	/**
	 * Strip HTML Tags, and Attributes
	 * from PHP.net by Nick
	 * <?php vString::stripTagsAttributes($string,'<strong><em><a>','href,rel'); ?>
	 * --
	 * @param	string	$string
	 * @param	string	$allowTags			<strong><em><a>
	 * @param	mixed	$allowAttributes	href,rel
	 * --
	 * @return	string
	 */
	public static function StripTagsAttributes($string, $allowTags=null, $allowAttributes=null)
	{
		if ($allowAttributes) {
			if (!is_array($allowAttributes)) {
				$allowAttributes = explode(',', $allowAttributes);
			}

			if (is_array($allowAttributes)) {
				$allowAttributes = implode('|', $allowAttributes);
			}

			$rep = '/([^>]*) ('.$allowAttributes.')(=)(\'.*\'|".*")/i';
			$string = preg_replace($rep, '$1 $2_-_-$4', $string);
		}

		if (preg_match('/([^>]*) (.*)(=\'.*\'|=".*")(.*)/i', $string) > 0) {
			$string = preg_replace('/([^>]*) (.*)(=\'.*\'|=".*")(.*)/i', '$1$4', $string);
		}

		$rep = '/([^>]*) ('.$allowAttributes.')(_-_-)(\'.*\'|".*")/i';

		if ($allowAttributes) {
			$string = preg_replace($rep, '$1 $2=$4', $string);
		}

		return strip_tags($string, $allowTags);
	}
	//-

	/**
	 * Will Convert HTML tags (< >) to save-for-output (&lt; &gt;)
	 * --
	 * @param	string	$input
	 * --
	 * @return	string
	 */
	public static function EncodeEntities($input)
	{
		$output = str_replace(array('<', '>'), array('&lt;', '&gt;'), $input);
		return $output;
	}
	//-

	/**
	 * Will Convert save-for-output tags (&lt; &gt;) back to HTML tags (< >)!
	 * --
	 * @param	string	$input
	 * --
	 * @return	string
	 */
	public static function RestoreEntities($input)
	{
		$output = str_replace(array('&lt;', '&gt;'), array('<', '>'), $input);
		return $output;
	}
	//-

	/**
	 * Get desired number of words - shorten string nicely...
	 * --
	 * @param	string	$input
	 * @param	integer	$numberOfWords
	 * --
	 * @return	string
	 */
	public static function GetWords($input, $numberOfWords)
	{
		$input = (string) $input;

		$Input = explode(' ', $input);
		$final = ''; $i = 0;
		if (is_array($Input)) {
			foreach ($Input as $word) {
				$final .= $word . ' ';

				if ($i >= $numberOfWords)
					break;
				else
					$i++;
			}
		}
		return rtrim($final);
	}
	//-

	/**
	 * Explode and trim data
	 * --
	 * @param	string	$seperator	If array, then we'll explode by and of them!
	 * @param	string	$str
	 * @param	integer	$limit
	 * --
	 * @return	array
	 */
	public static function ExplodeTrim($seperator, $str, $limit=false)
	{
		# If we wanna replace by more than one item!
		if (is_array($seperator)) {
			$sepFirst = $seperator[0];
			unset($seperator[0]);

			foreach ($seperator as $sep) {
				str_replace($sep, $sepFirst, $str);
			}

			$seperator = $sepFirst;
		}

		if ($limit !== false) {
			$D = explode($seperator, $str, $limit);
		}
		else {
			$D = explode($seperator, $str);
		}
		$F = array();

		if (is_array($D)) {
			foreach($D as $i) {
				$F[] = trim($i);
			}
		}
		return $F;
	}
	//-

	/**
	 * Will safely unserialize string
	 * --
	 * @param	string	$string		Serialized data
	 * @param	mixed	$default
	 * @param	string	$expected	Type of expected return array|boolean|string|numeric|object
	 * 								If we didn't got expected return, then we'll return default
	 * --
	 * @return mixed
	 */
	public static function Unserialize($string, $default=array(), $expected='array')
	{
		if (is_string($string) || empty($string)) {
			$return = unserialize($string);
			switch (strtolower($expected)) {
				case 'array':
					return (is_array($return)) ? $return : $default;
					break;
				case 'boolean':
				case 'bool':
					return (is_bool($return)) ? $return : $default;
					break;
				case 'string':
				case 'str':
					return (is_string($return)) ? $return : $default;
					break;
				case 'numeric':
					return (is_numeric($return)) ? $return : $default;
					break;
				case 'object':
					return (is_object($return)) ? $return : $default;
					break;
				default:
					return $default;
			}
		}
		else {
			Log::Add('INF', "Can't unserialize, provided data isn't string: `{$string}`.", __LINE__, __FILE__);
			return $default;
		}
	}
	//-

	/**
	 * Split text by particular delimiter and return particular piece of it.
	 * --
	 * @param	string	$delimiter
	 * @param	string	$string
	 * @param	integer	$piece
	 * @param	boolean	$limit
	 * --
	 * @return	string
	 */
	public static function Split($delimiter, $string, $piece, $limit=false)
	{
		$Return = self::ExplodeTrim($delimiter, $string, $limit);
		return isset($Return[$piece]) ? $Return[$piece] : false;
	}
	//-
	
	/**
	 * Will encode region, for example "region here" => ENC_base64_ENC
	 * --
	 * @param	mixed	$input		String or array of string, which will be processed
	 * @param	array	$markers	Define series of markers which we wanna encode, for example:
	 * 						array('"', '"') => "this will be encoded"
	 * 						array('<', '>') => <this will be encoded>
	 * 						array(array('#', '#'), array('%', '%')) =>
	 * 							#this will be encoded# AND %this will be encoded%
	 * 			Your markers should have at least one of following characters:
	 * 				' " < > ? ! # $ % & _ - [ ] { } ( ) = . : ; , | + *
	 * 			You markers can't have following characters:
	 * 				\
	 * --
	 * @return 	mixed	same as your input was!
	 */
	public static function EncodeRegion($input, $markers)
	{
		# If we got array
		if (is_array($input)) {
			foreach ($input as $k => $string) {
				$input[$k] = self::EncodeRegion($string, $markers);
			}

			return $input;
		}

		# Do we have array in markers?
		if (is_array($markers[0])) {
			foreach ($markers as $marker) {
				$input = self::EncodeRegion($input, $marker);
			}

			return $input;
		}

		# Characters which we should have, at least one
		$needToHave = array("'",'"', '<', '>', '?'. '!', '#', '$', 
							'%', '&', '_', '-', '[', ']', '{', '}', 
							'(', ')', '=', '.', ':', ';', ',', '|', '+', '*');

		# Character which we shouldn't have
		$cantHave = '\\';

		# Points
		$points = 0;

		# Check if we have what we shouldn't have
		if (strpos($markers[0], $cantHave) !== false || strpos($markers[1], $cantHave) !== false) {
			trigger_error("You have in your marker character (\) which isn't allowed!", E_USER_ERROR);
		}

		# Check if we have what we need
		foreach ($needToHave as $char) {
			if (strpos($markers[0], $char) !== false) {
				$points++;
				break;
			}
		}

		# Check if we have what we need
		foreach ($needToHave as $char) {
			if (strpos($markers[1], $char) !== false) {
				$points++;
				break;
			}
		}

		if ($points !== 2) {
			trigger_error(
				'You need to have one of the following characters in your markers: 
				\' " < > ? ! # $ % & _ - [ ] { } ( ) = . : ; , | + *',
				E_USER_ERROR);
		}

		# Alright now we should be clear to do what we need
		# First find all escaped characters
		if (strlen($markers[1]) === 1) {
			$input = preg_replace_callback(
				'/[\\\]('.preg_quote($markers[1]).')/', 
				create_function(
					'$matches',
					'return "ESCAPEREGIONESCCREE".base64_encode($matches[1])."EEESCAPEREGIONESCCR";'), 
				$input);
		}

		# Encode the region characters now
		$input = preg_replace_callback(
			'/('.preg_quote($markers[0]).'.*?'.preg_quote($markers[1]).')/', 
			create_function(
				'$matches',
				'return "ESCAPEREGIONCNTHEREEEEE".base64_encode($matches[1])."EEEEESCAPEREGIONCNTHERE";'),
			$input);

		return $input;
	}
	//-

/**
	 * Will decode region, for example ENC_base64_ENC => "region here"
	 * --
	 * @param	mixed	$input		String or array of string, which will be processed
	 * --
	 * @return 	mixed	same as your input was!
	 */
	public static function DecodeRegion($input)
	{
		# If we got array
		if (is_array($input)) {
			foreach ($input as $k => $string) {
				$input[$k] = self::DecodeRegion($string);
			}

			return $input;
		}

		# Decode the region characters now
		$input = preg_replace_callback(
			'/ESCAPEREGIONCNTHEREEEEE(.*?)EEEEESCAPEREGIONCNTHERE/', 
			create_function(
				'$matches',
				'return base64_decode($matches[1]);'),
			$input);

		# Find all escaped characters
		$input = preg_replace_callback(
			'/ESCAPEREGIONESCCREE(.*?)EEESCAPEREGIONESCCR/', 
			create_function(
				'$matches',
				'return base64_decode($matches[1]);'),
			$input);

		return $input;
	}
	//-
}
//--
