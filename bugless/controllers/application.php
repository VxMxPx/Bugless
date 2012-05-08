<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class applicationController
{
	# Make everything ready
	public function before()
	{
		# Load bugless config
		Cfg::Load('bugless');

		# General common functions
		Util::Get('functions');

		# Get general settings from database
		getDatabaseSettings();

		# Load english language
		Language::Load('main.en');

		# Add form globally
		$Form = new cForm();
		$Form->wrapFields('<div class="field {name} {type}">{field}</div>');
		View::AddVar('Form', $Form);

		# Add jQuery everywhere
		cJquery::Add();

		# Bugless javaScript
		cHTML::AddFooter('<script src="'.url('/js/bugless.min.js').'"></script>', 'bugless.js');
	}
	//-

	# Install the system
	public function install()
	{
		Util::Get('installer');
		$result = uInstaller::Run();

		if ($result === -1) {
			HTTP::Redirect(url());
		}

		Output::Set('Installer', Log::Get(2));
	}
	//-


	public function jsTagsCleanup($type)
	{
		uJSON::Response(tagsCleanup(Input::Post('tags'), $type));
	}
	//-

	# default 404
	public function not_found_404()
	{
		HTTP::Status404_NotFound('<h1>404: Not found</h1>');
	}
	//-
}
//--
