<!DOCTYPE html>
<?php if(!defined('PREPEND_PATH')) define('PREPEND_PATH', '../'); ?>
<?php if(!defined('ORIG_PATH')) define('ORIG_PATH', ''); ?>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="<?php echo datalist_db_encoding; ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Phrep | <?php echo (isset($x->TableTitle) ? $x->TableTitle : ''); ?></title>
		<link id="browser_favicon" rel="shortcut icon" href="<?php echo PREPEND_PATH; ?>resources/images/appgini-icon.png">

		<link rel="stylesheet" href="<?php echo ORIG_PATH; ?>resources/initializr/css/bootstrap.css">
		<link rel="stylesheet" href="<?php echo ORIG_PATH; ?>resources/lightbox/css/lightbox.css" media="screen">
		<link rel="stylesheet" href="<?php echo PREPEND_PATH; ?>resources/select2/select2.css" media="screen">

		<link rel="stylesheet" href="<?php echo PREPEND_PATH; ?>resources/timepicker/bootstrap-timepicker.min.css" media="screen">
		
		<link rel="stylesheet" href="<?php echo PREPEND_PATH; ?>resources/datepicker/css/datepicker.css" media="screen">		
		<link rel="stylesheet" href="<?php echo PREPEND_PATH; ?>resources/bootstrap-datetimepicker/bootstrap-datepicker.min.css" media="screen">
		<link rel="stylesheet" href="<?php echo ORIG_PATH; ?>dynamic.css.php">
		
                <!-- <link rel="stylesheet" href="<?php #echo PREPEND_PATH; ?>resources/datepicker/css/bootstrap-datepicker.standalone.css"> -->
                <link rel="stylesheet" href="<?php echo PREPEND_PATH; ?>resources/flags/css/flag-icon.css">
                <link rel="stylesheet" href="<?php echo PREPEND_PATH; ?>resources/flags/css/flag-icon.min.css">
				<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                
                <!--WYSIWYG editor-->
                <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>               
                <!--WYSIWYG editor-->
				
				
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.css" />
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/tokenfield-typeahead.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/tokenfield-typeahead.min.css">

		<!--[if lt IE 9]>
			<script src="<?php #echo PREPEND_PATH; ?>resources/initializr/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<![endif]-->
		<script src="<?php echo ORIG_PATH; ?>resources/jquery/js/jquery-1.12.4.min.js"></script>
		<script>var $j = jQuery.noConflict();</script>
		<script src="<?php echo PREPEND_PATH; ?>resources/moment/moment-with-locales.min.js"></script>
		<script src="<?php echo PREPEND_PATH; ?>resources/jquery/js/jquery.mark.min.js"></script>
		<script src="<?php echo ORIG_PATH; ?>resources/initializr/js/vendor/bootstrap.min.js"></script>
		<!-- <script src="<?php #echo PREPEND_PATH; ?>resources/lightbox/js/prototype.js"></script> -->
		<script src="<?php echo PREPEND_PATH; ?>resources/lightbox/js/scriptaculous.js?load=effects"></script>
		<!-- <script src="<?php #echo PREPEND_PATH; ?>resources/select2/select2.min.js"></script> -->
		<script src="<?php echo PREPEND_PATH; ?>resources/timepicker/bootstrap-timepicker.min.js"></script>
		<script src="<?php echo PREPEND_PATH; ?>resources/jscookie/js.cookie.js"></script>
		
		<!-- <script src="<?php #echo PREPEND_PATH; ?>resources/datepicker/js/datepicker.packed.js"></script>
		<script src="<?php #echo PREPEND_PATH; ?>resources/bootstrap-datetimepicker/bootstrap-datepicker.min.js"></script> -->

		<script src="<?php echo PREPEND_PATH; ?>common.js.php"></script>
		<?php if(isset($x->TableName) && is_file(dirname(__FILE__) . "/hooks/{$x->TableName}-tv.js")){ ?>
			<script src="<?php echo PREPEND_PATH; ?>hooks/<?php echo $x->TableName; ?>-tv.js"></script>
		<?php } ?>
		
		
						<!-- Global site tag (gtag.js) - Google Analytics -->
                        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-54608031-27"></script>
                        <script>
                          window.dataLayer = window.dataLayer || [];
                          function gtag(){dataLayer.push(arguments);}
                          gtag('js', new Date());

                          gtag('config', 'UA-54608031-27');
                        </script>  
		

	</head>
	<body>
		<div class="container theme-bootstrap theme-compact">
			<?php if(function_exists('handle_maintenance')) echo handle_maintenance(true); ?>

			<?php if(!$_REQUEST['Embedded']){ ?>
				<?php if(function_exists('htmlUserBar')) echo htmlUserBar(); ?>
				<div style="height: 70px;" class="hidden-print"></div>
			<?php } ?>

			<?php if(class_exists('Notification')) echo Notification::placeholder(); ?>

			<!-- process notifications -->
			<?php $notification_margin = ($_REQUEST['Embedded'] ? '15px 0px' : '-15px 0 -45px'); ?>
			<div style="height: 60px; margin: <?php echo $notification_margin; ?>;">
				<?php if(function_exists('showNotifications')) echo showNotifications(); ?>
			</div>

			<?php if(!defined('APPGINI_SETUP') && is_file(dirname(__FILE__) . '/hooks/header-extras.php')){ include(dirname(__FILE__).'/hooks/header-extras.php'); } ?>
			<!-- Add header template below here .. -->