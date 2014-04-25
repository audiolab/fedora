<?php 
/*
 * This is the template file for the pdf object
 *
 * @TODO: Add documentation about this file and the available variables
 */

//dsm(print_r($variables['islandora_object']['DC']->content,TRUE));
//dsm($variables['islandora_object']['MODS']->content);
//dsm(get_class_methods('IslandoraFedoraDatastream'));
//dsm(print_r($variables['islandora_mods']));
//dsm(print_r($variables['islandora_dublin_core'],TRUE));
//dsm($variables['mods_array']);
//dsm(print_r($variables['islandora_mods'],TRUE));
//dpm(print $viewer_url);
?>


<div class="islandora-pdf-object islandora">
  <div  class="islandora-pdf-content-wrapper clearfix">
    <?php if (isset($islandora_content)): ?>
      <div id ="object-content" class="islandora-pdf-content">
        <a href="<?php print $viewer_url ?>"><?php print $islandora_thumbnail_img ?></a>
      </div>
        <div>
            <?php
            $block = module_invoke('islandora_related', 'block_view', 'related_list');
            print $block['content'];
            ?>
        </div>
    <?php endif; ?>
  <div id="panel-metadata" class="islandora-pdf-sidebar">
      <h2 class="title"><?php print $mods_array['titleInfo']; ?></h2>
    <?php if (!empty($mods_array['abstract'])): ?>
        <div class="panel-pane">
            <div class="pane-title">
                <h3><?php print t('Abstract')?></h3>
            </div>
            <div class="pane-content listado-lineas">
                <div>
                    <p><?php print $mods_array['abstract']; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
	<div class="panel-pane">
		      <fieldset class="collapsible collapsed islandora-pdf-metadata pane-title">
		  	<legend><h3><span class="fieldset-legend"><?php print t('Full metadata record'); ?></span></h3></legend>
	          	<div class="fieldset-wrapper">
				<div class = "pane-content listado-lineas metadata-record">
			               <dl class="islandora-inline-metadata islandora-pdf-fields">
		               		<?php $row_field = 0; ?>
	        		          <?php //dpm($dc_array) ?>
        	        		  <?php foreach ($mods_array as $key => $value): ?>
	        	              <dt class="<?php print $row_field == 0 ? ' first' : ''; ?>">
        	        	          <?php print $key; ?>
	                	      </dt>
		                      <dd class="<?php print $row_field == 0 ? ' first' : ''; ?>">
        		                  <?php print $value; ?>
                		      </dd>
		                      <?php $row_field++; ?>
        		          <?php endforeach; ?>
			              </dl>
				</div>
		          </div>
		      </fieldset>	
	</div>
  </div>
  </div>

</div>
    
