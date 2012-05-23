<div class="page">
	<div class="box half center" id="forgot_password">
		<h2><?php le('FORGOT_PASSWORD'); ?></h2>
		<small class="faded"><?php le('FORGOT_PASSWORD_TAGLINE'); ?></small>
		<?php
		echo
			$Form->att('class="m_top styled"')->start('forgot_password'),
			$Form->textbox('email',   l('EMAIL_ADDRESS')),
			'<div class="field cancel">',
			cHTML::Link(l('CANCEL'), 'login', 'class="button plain"'),
			'</div>',
			$Form->button(l('SUBMIT')),
			$Form->end();
		?>
	</div>
</div>