<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Bugless</title>
	<meta name="description" content="An Open Source Bug Tracker." />
	<meta name="author" content="Marko Gajst" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php urle('/themes/brown/main.css'); ?>" />
	<link rel="icon" type="image/png" href="<?php urle('/themes/favicon.png'); ?>" />
	<?php cHTML::GetHeaders(); ?>
</head>
<body>
	<script>
		document.getElementsByTagName('body')[0].className = 'js';
	</script>
	<?php if (uMessage::Exists()) { echo '<div id="messages">', uMessage::Get(false), '</div>'; } ?>
	<div id="master">
		<div id="bucket">