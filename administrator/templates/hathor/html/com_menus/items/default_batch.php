<?php
/**
 * @version		$Id: default_batch.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$options = array(
	JHtml::_('select.option', 'c', JText::_('COM_MENUS_BATCH_COPY')),
	JHtml::_('select.option', 'm', JText::_('COM_MENUS_BATCH_MOVE'))
);
$published = (int) $this->state->get('filter.published');
?>
	<fieldset class="batch">
		<legend><?php echo JText::_('COM_MENUS_BATCH_OPTIONS');?></legend>
			
				<label id="batch-access-lbl" for="batch-access">
					<?php echo JText::_('COM_MENUS_BATCH_ACCESS_LABEL'); ?>
				</label>
				<?php echo JHtml::_('access.assetgrouplist', 'batch[assetgroup_id]', '', 'class="inputbox"', array('title' => '', 'id' => 'batch-access'));?>
			
				<?php if ($published >= 0) : ?>
					<label id="batch-choose-action-lbl" for="batch-menu-id">
						<?php echo JText::_('COM_MENUS_BATCH_MENU_LABEL'); ?>
					</label>
						<select name="batch[menu_id]" class="inputbox" id="batch-menu-id">
							<option></option>
							<?php echo JHtml::_('select.options', JHtml::_('menu.menuitems', array('published' => $published)));?>
						</select>
						<?php echo JHTML::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'); ?>
				<?php endif; ?>
			
			<button type="submit" onclick="submitbutton('item.batch');">
				<?php echo JText::_('COM_MENUS_BATCH_PROCESS'); ?>
			</button>
	</fieldset>
