<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

/**
 * Avrelia
 * ----
 * Permissions Model
 * ----
 * @package    Avrelia
 * @author     Avrelia.com
 * @copyright  Copyright (c) 2010, Avrelia.com
 * @license    http://framework.avrelia.com/license
 * @link       http://framework.avrelia.com
 * @since      Version 0.80
 * @since      2012-04-15
 */

class permissionsModel
{
	/**
	 * @var	array	List of all permissions
	 */
	private $Permissions;


	public function __construct()
	{
		$this->Permissions = array();
		$id = 0; # User's id

		# Get all general permissions
		$Query = new cDatabaseQuery();
		$Query
			->select()
			->from('permissions')
			->where('user_id', 0);

		if (cSession::IsLoggedin()) {
			$id = (int) cSession::AsArray('id');
			$Query->orWhere('user_id', $id);
		}

		$Result = $Query->execute();

		if ($Result->count() > 0) {
			$Permissions = $Result->asArray();

			$isAdmin = false;

			# Check, is it admin
			foreach ($Permissions as $Permission) {
				if ($Permission['action'] === 'is_admin' && (int) $Permission['user_id'] === $id)
				{
					$isAdmin = (int) $Permission['allowed'] === 1;
				}
			}

			foreach ($Permissions as $Permission)
			{
				vArray::Filter($Permission, array(
					'user_id'    => 'integer|',
					'action'     => 'string|',
					'allowed'    => 'integer[-1,2]|0'
				));

				if (isset($this->Permissions[$Permission['action']])) {
					# User's specific permissions always override actual
					if ($Permission['user_id'] === 0) {
						continue;
					}
				}

				# Fix for 1, if user not logged in
				if ($Permission['allowed'] === 1) {
					if ($Permission['user_id'] === 0) {
						if (!cSession::IsLoggedin()) {
							$Permission['allowed'] = 0;
						}
					}
				}

				# Fix value -1 (only unregistered)
				if ($Permission['allowed'] === -1) {

					if (cSession::IsLoggedin()) {
						$Permission['allowed'] = 0;
					}
					else {
						$Permission['allowed'] = 1;
					}
				}

				# Fix value 2 (only admin)
				if ($Permission['allowed'] === 2) {
					if ($isAdmin) {
						$Permission['allowed'] = 1;
					}
					else {
						$Permission['allowed'] = 0;
					}
				}

				$this->Permissions[$Permission['action']] = $Permission;
			}
		}
	}
	//-

	/**
	 * Can the action be accessed?
	 * --
	 * @param	string	$action
	 * --
	 * @return	boolean
	 */
	public function canAccess($action)
	{
		# Must exists, else we'll return false
		if (!isset($this->Permissions[$action])) {
			$return = false;
		}
		else {
			$return = $this->Permissions[$action]['allowed'] === 1 ? true : false;
		}

		Log::Add("Access controll for action `{$action}`: " . ($return ? 'allowed' : 'denied'), 'INF');
		return $return;
	}
	//-
}
//--
