<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* The following line loads the MooTools JavaScript Library */
JHTML::_('behavior.mootools');

$app = JFactory::getApplication();
?>
<?php echo '<?'; ?>xml version="1.0" encoding="<?php echo $this->_charset ?>"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
		<!-- The following JDOC Head tag loads all the header and meta information from your site config and content. -->
		<jdoc:include type="head" />
		
		<!-- The following four lines load the Blueprint CSS Framework (http://blueprintcss.org). If you don't want to use this framework, delete these lines. -->
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/screen.css" type="text/css" media="screen, projection">
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/print.css" type="text/css" media="print">
		<!--[if lt IE 8]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
    	<link rel="stylesheet" href="blueprint/plugins/joomla-nav/screen.css" type="text/css" media="screen">
		
		<!-- The following line loads the template CSS file located in the template folder. -->
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
		
		<!-- The following two lines load the Blueprint CSS Framework (http://blueprintcss.org) for right-to-left languages. If you don't want to use this framework, delete these lines. -->
		<?php if($this->direction == 'rtl') : ?>
			<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/plugins/rtl/screen.css" type="text/css" />
			<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template_rtl.css" rel="stylesheet" type="text/css" />
		<?php endif; ?>
		
		<!-- The following line loads the template JavaScript file located in the template folder. -->
		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/template.js"></script>
	</head>

	<body>
		<div class="container">
			<hr class="space">
			<div class="joomla-header span-16 append-1">
				<h1><?php echo $app->getCfg('sitename'); ?></h1>
			</div>
			<?php if($this->countModules('search')) : ?>
				<div class="joomla-search span-7 last">
	  	 			<jdoc:include type="modules" name="search" style="none" />
				</div>
			<?php endif; ?>
		</div>
		<?php if($this->countModules('mainmenu')) : ?>
			<div class="container">
				<jdoc:include type="modules" name="mainmenu" style="none" />
			</div>
		<?php endif; ?>	
		<div class="container">
			<div class="span-16 append-1">
				<jdoc:include type="message" />
				<jdoc:include type="component" />
        	</div>
		</div>
		<div class="span-7 last">
			<?php if($this->countModules('sidebar-top')) : ?>	
				<jdoc:include type="modules" name="sidebar-top" style="none" />
			<?php endif; ?>
		
			<?php if($this->countModules('sidebar-bottom')) : ?>	
        		<jdoc:include type="modules" name="sidebar-bottom" style="none" />
			<?php endif; ?>
		</div>
		<div class="joomla-footer span-16 append-1">
			&copy; <?php echo date('Y'); ?> <?php echo $app->getCfg('sitename'); ?>
		</div>
	</body>
</html>