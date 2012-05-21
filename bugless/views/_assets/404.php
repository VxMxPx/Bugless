<?php View::Get('_assets/header'); ?>
<?php View::Get('_assets/navigation_main'); ?>
<div class="page">
	<div class="box half center" id="error_404">
		<h2><?php le('404_TITLE'); ?></h2>
		<small class="faded"><?php le('404_DESCRIPTION'); ?></small>
		<p><?php le('404_ADVICE', lu('')); ?></p>
	</div>
</div>
<?php View::Get('_assets/footer'); ?>
