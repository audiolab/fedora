<?php
/**
 * @file views-isotope.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<div id="isotope-container">
  <?php
    $count = 0;
    foreach ($rows as $row):
	if (isset($isotope_filter_classes[$count])){
  ?>
    <div class="isotope-element <?php print $isotope_filter_classes[$count]; ?>" data-category="<?php print $isotope_filter_classes[$count]; ?>">
    <?php  
    }else{
    ?>
	<div class="isotope-element" data-category="">
	<?php } ?>
      <div class="isotope-element-wrapper"><?php print $row; ?></div>
    </div>
  <?php
      $count++;
    endforeach;
  ?>
</div>