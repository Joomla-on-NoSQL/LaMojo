<?php
/**
 * @version		$Id: default_button.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Administrator
 * @subpackage	mod_quickicon
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
?>
<div class="icon-wrapper">
	<div class="icon">
		<a href="<?php echo $button['link']; ?>">
			<?php echo JHTML::_('image','header/'.$button['image'], NULL, NULL, true); ?>
			<span><?php echo $button['text']; ?></span></a>
	</div>
</div>