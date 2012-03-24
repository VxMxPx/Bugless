<?php View::Get('_assets/header'); ?>

<div class="box half center" id="login">
	<h2><?php le('LOGIN'); ?></h2>
	<small class="faded"><?php le('LOGIN_TAGLINE'); ?></small>
	<?php
	echo
		$Form->att('class="m_top styled"')->start('login'),
		$Form->textbox('email',   l('EMAIL_ADDRESS')),
		$Form->masked('password', l('PASSWORD')),
		'<div class="field forgot_password">',
		cHTML::Link(l('FORGOT_MY_PASSWORD'), 'forgot_password'),
		'<br />',
		cHTML::Link(l('REGISTER_NEW_ACCOUNT'), 'register'),
		'</div>',
		$Form->button(l('LOGIN')),
		$Form->end();
	?>
</div>

<?php View::Get('_assets/footer'); ?>
