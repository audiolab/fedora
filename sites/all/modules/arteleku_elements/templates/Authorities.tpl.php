<?php
/**
 * @file
 * Template for the Tags Element.
 */
?>
<div class="arteleku-elements-authorities">
  <?php print $select; ?>

	<div class="hidden-tags">
		<?php foreach($authorities as $authority): ?>
			<input type = "hidden" data-lang="<?php print $authority['#lang']?>" data-tid = "<?php print $authority['#tid'] ?>" name = "<?php print $authority['#name'] ?>" id = "<?php print $authority['#id'] ?>" value = "<?php print $authority['#value'] ?>" /> 
		<?php endforeach; ?>
	
		<input type="button" style="display:none;" id="<?php print $add['#id']?>" name="<?php print $add['#name'] ?>">
	</div>
</div>
