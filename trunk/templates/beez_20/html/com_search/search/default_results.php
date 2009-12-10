<?php defined('_JEXEC') or die; ?>

<dl class="search_results<?php echo $this->params->get('pageclass_sfx'); ?>">

		<?php
		foreach($this->results as $result) : ?>


					<?php if ($result->href) :
					    echo '<dt class="result_title">';
						if ($result->browsernav == 1) : ?>
								<?php echo $this->pagination->limitstart + $result->count.'. ';?>
							<a href="<?php echo JRoute::_($result->href); ?>" target="_blank">
						<?php else : ?>
							<a href="<?php echo JRoute::_($result->href); ?>">

						<?php endif;

						echo $this->escape($result->title);

						if ($result->href) : ?>
							</a>
							</dt>
						<?php endif;
						if ($result->section) : ?>
						<dd class="result_category">

							<span class="small<?php echo $this->params->get('pageclass_sfx'); ?>">
								(<?php echo $this->escape($result->section); ?>)
							</span>
							</dd>
						<?php endif; ?>
					<?php endif; ?>

				<dd class="result_text">
					<?php echo $result->text; ?>
				</dd>
				<?php
					if ($this->params->get('show_date')) : ?>
				<dd class="small<?php echo $this->params->get('pageclass_sfx'); ?>">
					<?php echo $result->created; ?>
				</dd>
				<?php endif; ?>


		<?php endforeach; ?>
</dl>

<div class="pagination">
				<?php echo $this->pagination->getPagesLinks(); ?>
				</div>
