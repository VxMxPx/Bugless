<?php View::Get('_assets/header'); ?>
<?php View::Get('_assets/navigation_main'); ?>
<div class="page">
	<div class="box half center" id="register_after">
		<h2><?php le('REGISTER_ACCOUNT_AFTER'); ?></h2>
		<small class="faded"><?php le('REGISTER_ACCOUNT_AFTER_TAGLINE'); ?></small>
		<?php
		echo
			$Form->att('class="m_top styled"')->start('register'),
			$Form->textbox('full_name',   l('FULL_NAME')),
			$Form->wrapStart(),
				$Form->wrap(false)->att('class="half hook list_timezoneArray"')->select('continent', array(), l('TIMEZONE')),
				$Form->wrap(false)->att('class="half hook_second list_timezoneArray right"')->select('country', array()),
			$Form->wrapEnd(),
			$Form->select('language', getLanguagesList(), l('LANGUAGE')),
			'<div class="field cancel">',
				cHTML::Link(l('DONT_WANNA'), url(), 'class="button plain"'),
			'</div>',
			$Form->button(l('OK')),
			$Form->end();
		?>
	</div>
</div>
<?php View::Get('_assets/footer'); ?>
