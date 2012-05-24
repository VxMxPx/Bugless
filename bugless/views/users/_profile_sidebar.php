<div class="sidebar" id="profile_sidebar">
	<div class="box">
		<div class="group left">
			<h3><?php le('SEGMENTS'); ?></h3>
			<ul class="open_select">
				<li<?php if (Input::Get(1, false) === false) echo ' class="selected"'; ?>><a href="<?php urle('profile'); ?>"><?php le('GENERAL'); ?></a></li>
				<li<?php if (Input::Get(1, false) === 'edit_login') echo ' class="selected"'; ?>><a href="<?php urle('profile/edit_login'); ?>"><?php le('LOGIN_SETTINGS'); ?></a></li>
			</ul>
		</div>
	</div>
</div>
