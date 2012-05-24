<div id="navigation_main">
	<div class="left">
		<?php if (allow('dashboard')): ?>
		<a class="link_outside<?php if (!Input::Get(0)) echo ' active' ?>" href="<?php urle(); ?>"><?php le('DASHBOARD'); ?></a>
		<?php endif; ?>
		
		<a class="link_outside<?php if (Input::Get(0) === 'bug') echo ' active' ?>" href="<?php urle('bug/list'); ?>"><?php le('BUGS'); ?></a>
		<a class="link_outside<?php if (Input::Get(0) === 'milestone') echo ' active' ?>" href="<?php urle('milestone/list'); ?>"><?php le('MILESTONES'); ?></a>
		<a class="link_outside<?php if (Input::Get(0) === 'blueprint') echo ' active' ?>" href="<?php urle('blueprint/list'); ?>"><?php le('BLUEPRINTS'); ?></a>
		<a class="link_outside<?php if (Input::Get(0) === 'page') echo ' active' ?>" href="<?php urle('page/list'); ?>"><?php le('PAGES'); ?></a>
	</div>

	<div class="right">
		<form action="<?php urle('find'); ?>" method="post" id="find" class="outside">
			<input type="text" name="find" placeholder="<?php le('FIND'); ?>" />
			<button type="submit"><span><?php le('FIND'); ?></span></button>
		</form>
	</div>
</div>
