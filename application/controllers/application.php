<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

class applicationController
{
	# Make everything ready
	public function before()
	{
		# Load bugless config
		Cfg::Load('bugless');

		# Load english language
		Language::Load('main.en');

		# Add form globally
		$Form = new cForm();
		$Form->wrapFields('<div class="field {name} {type}">{field}</div>');
		View::AddVar('Form', $Form);
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

	# default 404
	public function not_found_404()
	{

	}
	//-
}
//--
