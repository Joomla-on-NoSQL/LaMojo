<?php

 /**
 * @version		$Id: default_form.php 11845 2009-05-27 23:28:59Z robs
 * @package		Joomla.Site
 * @subpackage	Contact
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');


 if (isset($this->error)) : ?>
	<div class="contact-error">
		<?php echo $this->error; ?>
	</div>
<?php endif; ?>

<div class="contact-form">
	<form id="emailForm" action="<?php echo JRoute::_('index.php?option=com_contact&task=contact.submit'); ?>" method="post" class="form-validate">

 	
			<div class="formelm">
				<?php echo $this->form->getLabel('contact_email'); ?>
				<?php echo $this->form->getInput('contact_email'); ?>
			</div>
		
			<div>
				<?php echo $this->form->getLabel('contact_subject'); ?>
				<?php echo $this->form->getInput('contact_subject'); ?>
			</div>
			<div>
				<?php echo $this->form->getLabel('contact-text'); ?>
				<?php echo $this->form->getInput('contact-text'); ?>
			</div>

			<?php if ($this->params->get('show_email_copy')) : ?>
			<div>
				<?php echo $this->form->getLabel('email_copy'); ?>
				<?php echo $this->form->getInput('email_copy'); ?>

			</div>
			<?php endif; ?>
			
				<button class="button validate" type="submit"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>
			</div>

		<input type="hidden" name="task" value="contact.submit" />
		<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo JHtml::_('form.token'); ?>
		</div>
<?php   ?>
	</form>
</div>
