<div class="sidebar" id="dashboard_list_sidebar">
	<?php if (allow('bug/submit')): ?>
	<div class="group">
		<a href="<?php urle('bug/submit'); ?>" class="button huge color bug_submit"><?php le('SUBMIT_BUG'); ?></a>
	</div>
	<?php endif; ?>

	<div class="box">
		<div class="group left">
			<h3><?php le('STATUS'); ?></h3>
			<ul class="open_select">
				<li class="selected"><a href="#"><?php le('ACTIVE'); ?><small>0</small></a></li>
				<li><a href="#"><?php le('FINISHED'); ?><small>0</small></a></li>
				<li><a href="#"><?php le('STARRED'); ?><small>0</small></a></li>
			</ul>
		</div>

		<div class="group left">
			<h3><?php le('TAGS'); ?></h3>
			<ul class="open_select">
				<li><a href="#"><span class="color_bullet" style="color:#e32;">&bullet;</span>Design<small>0</small></a></li>
				<li><a href="#"><span class="color_bullet" style="color:#23e;">&bullet;</span>Development<small>0</small></a></li>
			</ul>
		</div>
	</div>
</div>
