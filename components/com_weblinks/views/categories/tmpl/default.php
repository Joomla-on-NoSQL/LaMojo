<?php
/**
 * @version		$Id: default.php 12812 2009-09-22 03:58:25Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	com_newsfeeds
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

// If the page class is defined, wrap the whole output in a div.
$pageClass = $this->params->get('pageclass_sfx');
?>
<?php if ($this->params->get('show_page_title')) : ?>
<h1>
	<?php if ($this->escape($this->params->get('page_heading'))) :?>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	<?php else : ?>
		<?php echo $this->escape($this->params->get('page_title')); ?>
	<?php endif; ?>
</h1>
<?php endif; ?>
<div class="categories-list<?php echo $pageClass;?>">

<?php
echo $this->loadTemplate('items');
?>
</div>