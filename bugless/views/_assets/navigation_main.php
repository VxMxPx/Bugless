<div id="navigation_main">
	<div class="left">
		<?php if (allow('projects/list')) : ?>
			<a href="<?php urle(); ?>" class="link_outside"><small><?php le('DASHBOARD'); ?></small></a>
		<?php endif; ?>
		<?php if (allow('is_admin')) : ?>
			<a href="<?php urle('settings'); ?>" class="link_outside"><small><?php le('SETTINGS'); ?></small></a>
		<?php endif; ?>
	</div>

	<div class="right">
		<?php if (loggedin()): ?>
			<a href="<?php urle('profile'); ?>" class="link_outside"><small><?php le('HI_USER', userInfo('full_name|uname')); ?></small></a>
			<a href="<?php urle('logout'); ?>" class="link_outside"><small><?php le('LOGOUT'); ?></small></a>
		<?php else: ?>
			<a href="<?php urle('login'); ?>" class="link_outside"><small><?php le('LOGIN'); ?></small></a>
			<?php if (allow('register')) : ?>
			<a href="<?php urle('register'); ?>" class="link_outside"><small><?php le('REGISTER_ACCOUNT'); ?></small></a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
