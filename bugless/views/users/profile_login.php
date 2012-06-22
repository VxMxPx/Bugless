<div class="box wide left" id="profile_edit_login">
	<h2><?php le('LOGIN_SETTINGS'); ?></h2>
	<small class="faded"><?php le('EDIT_LOGIN_SETTINGS_TAGLINE', lh('strong')); ?></small>
	<?php
	echo
		$Form->att('class="m_top styled"')->defaults($Defaults)->start('profile/edit_login'),
		$Form->textbox('email',   l('EMAIL_ADDRESS')),
		$Form->checkbox('change_password', array('yes' => l('PASSWORD_CHANGE_CB'))),
		$Form->wrapStart('password_wrap js_hide'),
			$Form->wrap(false)->att('class="half"')->masked('password', l('PASSWORD_CHANGE_TWICE')),
			$Form->wrap(false)->att('class="half right"')->masked('password_again'),
		$Form->wrapEnd(),
		'<div class="field cancel">',
			cHTML::Link(l('CANCEL'), url(), 'class="button plain"'),
		'</div>',
		$Form->button(l('SAVE')),
		$Form->end();
	?>
</div>

<?php View::Get('users/_profile_sidebar'); ?>