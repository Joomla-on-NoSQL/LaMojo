<?php
/**
 * @version		$Id: thumbs_img.php 19670 2010-11-29 10:45:12Z chdemko $
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
$user = JFactory::getUser();
$params = new JRegistry;
$dispatcher	= JDispatcher::getInstance();
$dispatcher->trigger('onContentBeforeDisplay', array('com_media.file', &$this->_tmp_img, &$params));
?>
		<div class="imgOutline">
			<div class="imgTotal">
				<div class="imgBorder center">
					<a class="img-preview" href="<?php echo COM_MEDIA_BASEURL.'/'.$this->_tmp_img->path_relative; ?>" title="<?php echo $this->_tmp_img->name; ?>" style="display: block; width: 100%; height: 100%">
						<?php echo JHTML::_('image',COM_MEDIA_BASEURL.'/'.$this->_tmp_img->path_relative, JText::sprintf('COM_MEDIA_IMAGE_TITLE', $this->_tmp_img->title, MediaHelper::parseSize($this->_tmp_img->size)), array('width' => $this->_tmp_img->width_60, 'height' => $this->_tmp_img->height_60, 'border' => 0)); ?>
					</a>
				</div>
			</div>
			<div class="controls">
			<?php if ($user->authorise('core.delete','com_media')):?>
				<a class="delete-item" href="index.php?option=com_media&amp;task=file.delete&amp;tmpl=component&amp;<?php echo JUtility::getToken(); ?>=1&amp;folder=<?php echo $this->state->folder; ?>&amp;rm[]=<?php echo $this->_tmp_img->name; ?>" rel="<?php echo $this->_tmp_img->name; ?>"><?php echo JHTML::_('image','media/remove.png', JText::_('JACTION_DELETE'), array('width' => 16, 'height' => 16, 'border' => 0), true); ?></a>
				<input type="checkbox" name="rm[]" value="<?php echo $this->_tmp_img->name; ?>" />
			<?php endif;?>
			</div>
			<div class="imginfoBorder">
				<a href="<?php echo COM_MEDIA_BASEURL.'/'.$this->_tmp_img->path_relative; ?>" title="<?php echo $this->_tmp_img->name; ?>" class="preview"><?php echo $this->escape(substr($this->_tmp_img->title, 0, 10) . (strlen($this->_tmp_img->title) > 10 ? '...' : '')); ?></a>
			</div>
		</div>
<?php
$dispatcher->trigger('onContentAfterDisplay', array('com_media.file', &$this->_tmp_img, &$params));
?>