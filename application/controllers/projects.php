<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class projectsController
{
	public function dashboard()
	{
		View::Get('projects/list');
	}
	//-
}
//--
