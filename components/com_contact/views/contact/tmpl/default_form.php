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
	<form id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate">
		<?php foreach ($this->form->getFieldsets() as $fieldset): ?>
			<?php $fields = $this->form->getFieldset($fieldset->name);?>
			<?php if (count($fields)):?>
				<fieldset>
				<?php if (isset($fieldset->label)):?>
					<legend><?php echo JText::_($fieldset->label);?></legend>
				<?php endif;?>
					<dl>
				<?php foreach($fields as $field): ?>
					<?php if ($field->hidden): ?>
						<?php echo $field->input;?>
					<?php else:?>
						<dt>
						<?php echo $field->label; ?>
						<?php if (!$field->required && (!$field->type == "spacer")): ?>
							<span class="optional"><?php echo JText::_('COM_CONTACT_OPTIONAL');?></span>
						<?php endif; ?>
						</dt>
						<dd><?php echo $field->input;?></dd>
					<?php endif;?>
				<?php endforeach;?>
					</dl>
				</fieldset>
			<?php endif;?>
		<?php endforeach;?>
		<div>
			<button class="button validate" type="submit"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>
			<input type="hidden" name="option" value="com_contact" />
			<input type="hidden" name="task" value="contact.submit" />
			<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</div>
	</form>
</div>