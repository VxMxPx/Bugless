<div class="page has_sidebar" id="dashboard">
	<div class="box no_activity">
		<span class="c_ico pointer_r"></span>
		<p class="faded"><?php echo allow('bug/submit') ? l('DASHBOARD_NOTHING_TO_DO', lh('span.dark')) : l('DASHBOARD_NOTHING_TO_DO_CANT_SUBMIT'); ?></p>
		<?php if (allow('bug/submit')): ?>
			<br />
			<h2><?php le('DASHBOARD_SUBMIT_BUG', lh('a(bug/submit).bug_submit strong')); ?></h2>
		<?php endif; ?>
	</div>
</div> <!-- /#projects_dashboard -->

<?php View::Get('dashboard/_list_sidebar'); ?>