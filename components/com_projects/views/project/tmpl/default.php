<?php
/**
 * @version		$Id:
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

// Vars
$params = $this->params;
$pageClass = $this->params->get('pageclass_sfx');
?>
<div class="projects<?php echo $pageClass ?>">
    <div class="two3">
    	<div class="projects-overview">
	        <form action="<?php echo ProjectsHelper::getLink('project', $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	            <div class="projects-module">
					<h4><?php echo JText::_('COM_PROJECTS_PROJECT_DETAILS_TITLE');?></h4>
					<div class="projects-content">
		                <div class="projects-space">
		                	<div class="projects-both-sides">
			                    <span><?php echo JText::_('COM_PROJECTS_FIELD_START_AT_LABEL') ?>:</span>
			                    <span><?php echo JFactory::getDate($this->item->start_at)->toFormat('%d.%m.%Y'); ?></span>
		                	</div>
		                	<div class="projects-both-sides">
								<span><?php echo JText::_('COM_PROJECTS_FIELD_FINISH_AT_LABEL') ?>:</span>
								<span><?php echo JFactory::getDate($this->item->finish_at)->toFormat('%d.%m.%Y'); ?></span>
		                    </div>
		                </div>
	                    <?php echo JHTML::_('tool.progressBar', $this->item->progress); ?>
	                </div>
	            </div>
	            <div class="projects-module">
					<h4><?php echo JText::_('COM_PROJECTS_PROJECT_DESCRIPTION_TITLE');?></h4>
					<div class="projects-content">	            
					<?php echo $this->item->description; ?>
					</div>
	            </div>
	
	        <?php if ($params->get('show_activities') || $this->canDo->get('activity.view') || false): ?>
	            <div class="project-activities projects-space">
	                <?php echo $this->loadTemplate('activities'); ?>
	            </div>
	        <?php endif; ?>
	
	            <input type="hidden" name="task" value="" />
	            <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	            <input type="hidden" name="boxchecked" value="1" />
	            <?php echo JHTML::_('form.token'); ?>
	        </form>
	    </div>
    </div>

    <div class="one3">
		<?php
		// Tasks and Milestones
		if ($this->canDo->get('task.view')) {
			echo $this->loadTemplate('tasks');
		}

		// Tickets
		if ($this->canDo->get('ticket.view')) {
			echo $this->loadTemplate('tickets');
		}

		// Documents
		if ($this->canDo->get('document.view')) {
			echo $this->loadTemplate('documents');
		}

		// Members
		if ($this->canDo->get('members.view')) {
			echo $this->loadTemplate('members');
		}
		?>
    </div>
</div>
