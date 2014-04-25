<?php

/**
* Theme function for the compound navigation block parts.
*/
function theme_islandora_preview($variables) {

    module_load_include('inc', 'islandora', 'includes/utilities');

    $object = $variables['islandora_object'];
    // Optional pager parameters.
    $output = array();
    $hooks = islandora_build_hook_list(ISLANDORA_PREVIEW_HOOK, $object->models);
    foreach ($hooks as $hook) {
        // @todo Remove page number and size from this hook, implementers of the
        // hook should use drupal page handling directly.
        $temp = module_invoke_all($hook, $object);
        if (!empty($temp)) {
            $output = array_merge_recursive($output, $temp);
        }
    }
    if (empty($output)) {
        // No results, use the default view.
        $output = islandora_default_islandora_preview_object($object);
    }
    arsort($output);
    drupal_alter($hooks, $object, $output);
    return implode('', $output);
}

/**
 * Implements hook_preprocess_theme().
 */
function islandora_preprocess_islandora_default_preview(&$variables) {
    drupal_add_js('misc/form.js');
    drupal_add_js('misc/collapse.js');
    $islandora_object = $variables['islandora_object'];
    module_load_include('inc', 'islandora', 'includes/utilities');
    module_load_include('inc', 'islandora', 'includes/datastream');


    $pid = $islandora_object->id;
    $object_url = 'islandora/object/' . $pid;
    $title = $islandora_object->label;
    $class = drupal_strtolower(preg_replace('/[^A-Za-z0-9]/', '-', $pid));

    /*if (isset($fc_object['TN'])) {
        $thumbnail_img = theme('image', array('path' => "$object_url/datastream/TN/view"));
    }
    else {
        $image_path = drupal_get_path('module', 'islandora');
        $thumbnail_img = theme('image', array('path' => "$image_path/images/folder.png"));
    }*/
    $title_link = l($title, $object_url, array('html' => TRUE, 'attributes' => array('title' => $title)));
    //$associated_objects_array[$pid]['thumb_link'] = l($thumbnail_img, $object_url, array('html' => TRUE, 'attributes' => array('title' => $title)));
    $variables['title_link'] = $title_link;
}

