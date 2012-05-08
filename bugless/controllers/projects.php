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
		cHTML::AddHeader('<script> var Bugless_TagsCleanupUrl = "'.url('tags-cleanup.json/projects').'";</script>', 'projectsAddTagsCheckUrl');
		View::Get('projects/list');
	}
	//-
}
//--
