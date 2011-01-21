<?php
/**
 * @version		$Id: edit.php 18740 2010-08-31 19:21:09Z 3dentech $
 * @package		Joomla.Administrator
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	function submitbutton(task)
	{
		if (task == 'project.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			submitform(task);
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_projects&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_PROJECTS_PROJECT_NEW_PROJECT') : JText::sprintf('COM_PROJECTS_PROJECT_EDIT_PROJECT', $this->item->id); ?></legend>
			<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('catid'); ?>
			<?php echo $this->form->getInput('catid'); ?></li>

			<li><?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?></li>
			
			<li><?php echo $this->form->getLabel('start_at'); ?>
			<?php echo $this->form->getInput('start_at'); ?></li>

			<li><?php echo $this->form->getLabel('finish_at'); ?>
			<?php echo $this->form->getInput('finish_at'); ?></li>
			

			<li><?php echo $this->form->getLabel('state'); ?>
			<?php echo $this->form->getInput('state'); ?></li>

			</ul>
			
			<div class="clr"></div>
			<?php echo $this->form->getInput('description'); ?>

		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<?php echo JHtml::_('sliders.start','newsfeed-sliders-'.$this->item->id, array('useCookie'=>1)); ?>

		<?php echo JHtml::_('sliders.panel',JText::_('JGLOBAL_FIELDSET_PUBLISHING'), 'publishing-details'); ?>

		<fieldset class="panelform">
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('access'); ?>
				<?php echo $this->form->getInput('access'); ?></li>
				
				<li><?php echo $this->form->getLabel('language'); ?>
				<?php echo $this->form->getInput('language'); ?></li>
				
				<li><?php echo $this->form->getLabel('created_by'); ?>
				<?php echo $this->form->getInput('created_by'); ?></li>

				<li><?php echo $this->form->getLabel('created_by_alias'); ?>
				<?php echo $this->form->getInput('created_by_alias'); ?></li>

				<li><?php echo $this->form->getLabel('created'); ?>
				<?php echo $this->form->getInput('created'); ?></li>

				<li><?php echo $this->form->getLabel('publish_up'); ?>
				<?php echo $this->form->getInput('publish_up'); ?></li>

				<li><?php echo $this->form->getLabel('publish_down'); ?>
				<?php echo $this->form->getInput('publish_down'); ?></li>

				<li><?php echo $this->form->getLabel('modified'); ?>
				<?php echo $this->form->getInput('modified'); ?></li>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
			</ul>
		</fieldset>
	</div>
	<div class="clr"></div>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>