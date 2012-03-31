<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class projectsController
{
	public function __construct()
	{
		Language::Load('main.en');

		# Add form globally
		$Form = new cForm();
		$Form->wrapFields('<div class="field {name} {type}">{field}</div>');
		View::AddVar('Form', $Form);
	}
	//-

	public function dashboard()
	{
		View::Get('projects/list');
	}
	//-
}
//--
