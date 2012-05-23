<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class dashboardController
{
	public function index()
	{
		if (!allow('dashboard', true)) { return false; }
		View::Get('_assets/master')->asMaster();
		View::Get('dashboard/list')->asRegion('main');
	}
	//-
}
//--
