<?php
/**
 * @version		$Id: default_cloud_server.php 17109 2010-05-16 21:03:55Z severdia $
 * @package		Joomla.Administrator
 * @subpackage	com_config
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
?>
<script type="text/javascript">
window.addEvent('domready', function(){
	if(document.id('jform_storage_type').get("value")=='local'){
		document.id('jform_cloud_acc_name').set('class', '');
		document.id('jform_cloud_access_key').set('class', '');
	}
	document.id('jform_storage_type').addEvent('change',function() {
		if(this.get("value")!='local'){
			document.id('jform_cloud_acc_name').set('class', 'required');
			document.id('jform_cloud_access_key').set('class', 'required');
		  }else{
			  document.id('jform_cloud_acc_name').set('class', '');
			  document.id('jform_cloud_access_key').set('class', '');
		  }
	});
});
</script>
<div class="width-100">
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_CONFIG_CLOUD_SERVER_SETTINGS'); ?></legend>
	<ul class="adminformlist">
			<?php
			foreach ($this->form->getFieldset('cloud_server') as $field):
			?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
			<?php
			endforeach;
			?>
		</ul>
</fieldset>
</div>