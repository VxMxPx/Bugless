<div class="page">
	<div class="box full center" id="not_allowed">
		<h2><?php le('NOT_ALLOWED_TITLE') ?></h2>
		<!-- small class="faded"></small -->
		<p class="message">
		<?php le('NOT_ALLOWED_MESSAGE'); ?>
		<?php
			if (allow('login')) {

				echo '<br />';

				if (allow('register')) {
					le('NOT_ALLOWED_LOGIN_OR_REGISTER', lu('login', 'register'));
				}
				else {
					le('NOT_ALLOWED_LOGIN', lu('login'));
				}
			}
		?>
		</p>
	</div>
</div>