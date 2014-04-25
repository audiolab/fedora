<?php

/**
 * @file
 * Render a bunch of objects in a list or grid view.
 */
?>
<div class="cuatro-columnas-adaptativo">
    <div id="primera-adaptativo">
        <div class="panel-pane pane-title-line panel-no-margin">
            <div class="pane-title"><h3><?print t('Colecciones')?></h3></div>
            <div class="pane-content">
                <div class="islandora islandora-basic-collection">
                    <div class="islandora-basic-collection clearfix">
                        <?php foreach($associated_objects_array as $key => $value): ?>
                            <dl class="islandora-basic-collection-object <?php print $value['class']; ?>">
                                <dt class="islandora-basic-collection-thumb"><?php print $value['thumb_link']; ?></dt>
                                <dd class="islandora-basic-collection-caption"><h1><?php print $value['title_link']; ?></h1></dd>
                            </dl>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="sub-columna">
        <div class="panel-pane pane-title-line panel-no-margin">
            <div class="pane-title"><h3><?print t('Últimos archivos')?></h3></div>
            <div class="pane-content">
                <?php print views_embed_view('ultimos_objectos', 'block')?>
            </div>
        </div>
    </div>
</div>
