<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class projectsController
{
	public function __construct()
	{
		jsController('projects');
	}
	//-

	public function dashboard()
	{
		if (!allow('projects/list', true)) { return false; }

		View::Get('projects/list');
	}
	//-
}
//--
