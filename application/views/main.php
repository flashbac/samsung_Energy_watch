<?php $this -> load -> helper('url'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<!-- Mybe css File Import -->
		<title><?= $pageTitle ?></title>
		<META HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
		<link type="text/css" href="<?php echo base_url() ?>css/header.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo base_url() ?>css/navi.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo base_url() ?>css/error.css" rel="stylesheet" />
		<!-- <link type="text/css" href="<?php echo base_url() ?>/css/login.css" rel="stylesheet" /> -->
		<!-- <link type="text/css" href="<?php echo base_url()."css/content.css" ?>" rel="stylesheet" /> -->
		<!--Laden der conten css Datei -->
		<?php
		if (isset($cssfile))
		{ 
		?>
		<link type="text/css" href="<?php echo base_url()."css/".$cssfile ?>" rel="stylesheet" />
		<?php
		}
		?>
		<?php
		if (isset($jsfile))
		{?>
		<script type="text/javascript" src="<?php echo base_url()."js/".$jsfile ?>"></script>
		<?php
		}
		?>
		<script type="text/javascript" src="<?= base_url() ?>/js/jquery-1.7.2.js"></script>
	</head>
	<body>
		<div id="header">
			<?= $header ?>
		</div>
		<div id="navi">
			<?= $navigation ?>
		</div>
		<div id="content">
			<?= $content ?>
		</div>
		
	</body>
 
</html>