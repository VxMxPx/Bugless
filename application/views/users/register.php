<?php View::Get('_assets/header'); ?>

<div class="box half center" id="register">
	<h2><?php le('REGISTER_ACCOUNT'); ?></h2>
	<small class="faded"><?php le('REGISTER_ACCOUNT_TAGLINE'); ?></small>
	<?php
	echo
		$Form->att('class="m_top styled"')->start('register'),
		$Form->textbox('email',   l('EMAIL_ADDRESS')),
		$Form->masked('pwd', l('PASSWORD')),
		$Form->masked('pwd_again', l('PASSWORD_AGAIN')),
		'<div class="field cancel">',
		cHTML::Link(l('CANCEL'), 'login', 'class="button plain"'),
		'</div>',
		$Form->button(l('REGISTER')),
		$Form->end();
	?>
</div>

<?php View::Get('_assets/footer'); ?>
