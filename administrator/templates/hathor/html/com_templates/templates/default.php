<?php
/**
 * @version		$Id: default.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_templates
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

$user = & JFactory::getUser();
?>

<form action="<?php echo JRoute::_('index.php?option=com_templates&view=templates'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('Filters'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('COM_TEMPLATES_TEMPLATES_FILTER_SEARCH_DESC'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select">
			<label class="selectlabel" for="filter_client_id">
				<?php echo JText::_('COM_TEMPLATES_TEMPLATES_FILTER_CLIENT'); ?>
			</label>
			<select name="filter_client_id" id="filter_client_id" class="inputbox">
				<option value="*"><?php echo JText::_('COM_TEMPLATES_TEMPLATES_FILTER_CLIENT'); ?></option>
				<?php echo JHtml::_('select.options', TemplatesHelper::getClientOptions(), 'value', 'text', $this->state->get('filter.client_id'));?>
			</select>
			
			<button type="button" id="filter-go" onclick="this.form.submit();">
				<?php echo JText::_('Go'); ?></button>
			
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist" id="template-mgr">
		<thead>
			<tr>
				<th class="checkmark-col">
					&nbsp;
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_TEMPLATE', 'a.element', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-10">
					<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_TYPE', 'a.client_id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="center width-10">
					<?php echo JText::_('Version'); ?>
				</th>
				<th class="width-15">
					<?php echo JText::_('Date'); ?>
				</th>
				<th class="width-25">
					<?php echo JText::_('Author'); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('templates.thumb', $item->element, $item->client_id); ?>
				</td>
				<td class="template-name">
					<a href="<?php echo JRoute::_('index.php?option=com_templates&view=template&id='.(int) $item->extension_id); ?>">
						<?php echo $item->name;?></a>
				</td>
				<td class="center">
					<?php echo $item->client_id == 0 ? JText::_('COM_TEMPLATES_OPTION_SITE') : JText::_('COM_TEMPLATES_OPTION_ADMINISTRATOR'); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->xmldata->get('version')); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->xmldata->get('creationdate')); ?>
				</td>
				<td>
					<?php if ($author = $item->xmldata->get('author')) : ?>
						<p><?php echo $this->escape($author); ?></p>
					<?php else : ?>
						&mdash;
					<?php endif; ?>
					<?php if ($email = $item->xmldata->get('authorEmail')) : ?>
						<p><?php echo $this->escape($email); ?></p>
					<?php endif; ?>
					<?php if ($url = $item->xmldata->get('authorUrl')) : ?>
						<p><a href="<?php echo $this->escape($url); ?>">
							<?php echo $this->escape($url); ?></a></p>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php echo $this->pagination->getListFooter(); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
