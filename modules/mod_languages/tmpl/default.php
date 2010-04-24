<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_languages
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('stylesheet','mod_languages/template.css', array(), true);
?>
<div class="mod_languages<?php echo $params->get('moduleclass_sfx') ?>">
<?php if ($headerText) : ?>
	<div class="header"><?php echo $headerText; ?></div>
<?php endif; ?>
		<ul>
<?php foreach($list as $language):?>
			<li>
				<a href="<?php echo JRoute::_('index.php?' . 'Itemid=' . $language->id . '&language=' . $language->lang_code);?>">
	<?php echo JHtml::_('image','mod_languages/'.$language->image.'.gif',$language->title,array('title'=>$language->title),true);?>
				</a>
			</li>
<?php endforeach;?>
		</ul>
<?php if ($footerText) : ?>
	<div class="footer"><?php echo $footerText; ?></div>
<?php endif; ?>
</div>
