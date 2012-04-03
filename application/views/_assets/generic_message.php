<?php View::Get('_assets/header'); ?>
<?php View::Get('_assets/navigation_main'); ?>
<div class="page">
	<div class="box half center" id="generic_message">
		<?php if (isset($title)): ?>
			<h2><?php echo $title; ?></h2>
		<?php endif; ?>

		<?php if (isset($description)): ?>
			<small class="faded"><?php echo $description; ?></small>
		<?php endif; ?>

		<?php if (isset($message)): ?>
			<p class="message"><?php echo $message; ?></p>
		<?php endif; ?>

		<?php if (isset($button_1) || isset($button_2)): ?>
			<p class="buttons">
				<?php
				echo (isset($button_1) ? $button_1 : ''),
					 (isset($button_2) ? $button_2 : '');
				?>
			</p>
		<?php endif; ?>
	</div>
</div>
<?php View::Get('_assets/footer'); ?>
