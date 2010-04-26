<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include HTML helpers.
JHTML::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHTML::_('behavior.tooltip');

$lDirection = $this->state->get('list.direction');
$lOrdering	= $this->state->get('list.ordering');
?>

<form action="<?php echo JRoute::_('index.php?option=com_social&view=threads');?>" method="post" name="adminForm">

	<div class="form-filter" style="float: left;">
		<label for="filter_search"><?php echo JText::_('COM_SOCIAL_SEARCH_LABEL'); ?></label>
		<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_SOCIAL_SEARCH_TITLE');?>"/>
		<button onclick="this.form.submit();"><?php echo JText::_('COM_SOCIAL_SEARCH_GO'); ?></button>
		<button onclick="document.getElementById('filter_search').value='';document.getElementById('published').value='0';this.form.submit();"><?php echo JText::_('COM_SOCIAL_SEARCH_RESET'); ?></button>
	</div>

	<div class="form-filter" style="float: right;">
		<select name="filter_context" id="filter_context" class="inputbox" onchange="this.form.submit()">
			<option value=""><?php echo JText::_('COM_SOCIAL_All_Contexts');?></option>
			<?php echo JHtml::_('select.options', SocialHelper::getContextOptions(), 'value', 'text', $this->state->get('filter.context'));?>
		</select>
	</div>

	<table class="adminlist" style="clear: both;">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(this,'cid')" />
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_SOCIAL_Url_Heading', 'a.name', $lDirection, $lOrdering); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_SOCIAL_Page_Title_Heading', 'a.name', $lDirection, $lOrdering); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_SOCIAL_Thread_Date_Heading', 'a.created_time', $lDirection, $lOrdering); ?>
				</th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_SOCIAL_Comment_Count_Heading', 'comment_count', $lDirection, $lOrdering); ?>
				</th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_SOCIAL_Rating_Count_Heading', 'pscore_count', $lDirection, $lOrdering); ?>
				</th>
				<th width="1%">
					<?php echo JHTML::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $lDirection, $lOrdering); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td align="center">
					<?php echo JHTML::_('grid.id', $item->id, $item->id); ?>
				</td>
				<td>
					<?php echo $this->escape($item->page_url); ?>
				</td>
				<td>
					<?php echo $this->escape($item->page_title); ?>
				</td>
				<td align="center">
					<?php echo JHTML::_('date',$item->created_time, JText::_('DATE_FORMAT_LC3')); ?>
				</td>
				<td align="center">
					<?php if ($item->comment_count) : ?>
						<?php echo (int) $item->comment_count; ?>
						<br />
						<a href="<?php echo JRoute::_('index.php?option=com_social&task=threads.resetcomments&id='.(int) $item->id.'&'.JUtility::getToken().'=1');?>">
							<?php echo JText::_('COM_SOCIAL_Reset'); ?></a>
					<?php else : ?>
					-
					<?php endif; ?>
				</td>
				<td align="center">
					<?php if ($item->pscore_count) : ?>
						<?php echo (int) $item->pscore_count; ?>
						<br />
						<a href="<?php echo JRoute::_('index.php?option=com_social&task=threads.resetratings&id='.(int) $item->id.'&'.JUtility::getToken().'=1');?>">
							<?php echo JText::_('COM_SOCIAL_Reset'); ?></a>
					<?php else : ?>
					-
					<?php endif; ?>
				</td>
				<td align="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
	<input type="hidden" name="task" value="" />

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $lOrdering; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $lDirection; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>