<div class="box wide left" id="profile_personal">
	<h2><?php le($first ? 'ACCOUNT_ACTIVATE' : 'PERSONAL_INFORMATION'); ?></h2>
	<small class="faded"><?php le($first ? 'ACCOUNT_ACTIVATE_TAGLINE' : 'EDIT_PROFILE_TAGLINE'); ?></small>
	<?php
	echo
		$Form->att('class="m_top styled"')->defaults($Defaults)->start('profile'),
		$Form->textbox('full_name',   l('FULL_NAME')),
		$Form->wrapStart('timezone_wrap'),
			$Form->wrap(false)->att('class="half tz_continent"')->select('continent', array(), l('TIMEZONE_CURRENT', date('H:i'))),
			$Form->wrap(false)->att('class="half tz_country right"')->select('country', array()),
		$Form->wrapEnd(),
		$Form->select('language', getLanguagesList(), l('LANGUAGE')),
		'<div class="field cancel">',
			cHTML::Link(l($first ? 'DONT_WANNA' : 'CANCEL'), url(), 'class="button plain"'),
		'</div>',
		$Form->button(l('SAVE')),
		$Form->end();
	?>
</div>

<?php View::Get('users/_profile_sidebar'); ?>