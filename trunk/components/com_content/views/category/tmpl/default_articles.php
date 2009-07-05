<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php if (empty($this->articles)) : ?>
	no articles
<?php else : ?>

	<table>
		<?php foreach ($this->articles as &$item) : ?>
		<tr>
			<td>
				<a href="<?php echo JRoute::_(ContentRoute::article($item->slug, $item->catslug)); ?>">
					<?php echo $item->title; ?></a>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>

<?php endif; ?>
