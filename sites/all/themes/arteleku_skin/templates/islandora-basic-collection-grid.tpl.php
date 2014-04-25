<?php

/**
 * @file
 * Render a bunch of objects in a list or grid view.
 */
?>
<div class="islandora islandora-arteleku-collection">
    <div class="islandora-arteleku-collection-grid clearfix">

        <div class="panel-pane pane-title-line panel-no-margin">
            <div class="pane-title"><h3><?print $islandora_object_label?></h3></div>
            <div class="pane-content">
            <?php $rows = array() ?>
            <?php foreach($associated_objects_array_mods as $key => $value):
                isset($value['mods_array']['abstract'])? $ab = views_trim_text(array('max_length'=> 150, 'word_boundary'=>true, 'ellipsis'=>true),$value['mods_array']['abstract']) : $ab ="";
                $rows[] = array(
                    array('data' => "<h3>{$value['title_link']}</h3>", 'class' => array("title-cell")),
                    $ab
                );
             endforeach; ?>

                <?php
                $table_element = array(
                    '#theme' => 'table',
                    '#header' =>  array('title' => t('Title'), 'abstract' => t('Abstract')),
                    '#rows' => $rows,
                    '#empty' =>t('Your table is empty'),
                    '#sticky' => false,
                    '#attributes' => array('id' => 'islandora-collection-table')
                );
                print drupal_render($table_element);

                ?>

            </div>
        </div>
    </div>
</div>

