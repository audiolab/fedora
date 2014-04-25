<?php

/**
 * @file
 * Admin functionality.
 */

/**
 * Admin form building function.
 */
function arteleku_collection_page_admin_settings($form, &$form_state) {
  // Include admin CSS file.
  $admin_css = drupal_get_path('module', 'arteleku_collection_page') . '/css/arteleku_collection_page.admin.css';
  drupal_add_css($admin_css);

  $form['collection_pages'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );

  // Fields.
  $form['collection_pages']['arteleku_collection_page_collection_data'] = array(
    '#type' => 'item',
    '#title' => t('Collection pages'),
    '#description' => t('Configure facet pages. Save settings for additional empty fields.'),
    '#tree' => TRUE,
    '#theme' => 'arteleku_collection_page_admin_table',
  );

  // Get fields from variable.
  $fields_data = variable_get('arteleku_collection_page_collection_data', array());

  // Add 3 empty fields.
  for ($i = 1; $i <= 3; $i++) {
    $fields_data[] = array('');
  }

  $fields = array();
  foreach ($fields_data as $key => $value) {
    $field = array(
      'collection_field' => array(
        '#type' => 'textfield',
        '#default_value' => isset($value['collection_field']) ? $value['collection_field'] : '',
      ),
      'label' => array(
        '#type' => 'textfield',
        '#default_value' => isset($value['label']) ? $value['label'] : '',
      ),
      'path' => array(
        '#type' => 'textfield',
        '#default_value' => isset($value['path']) ? $value['path'] : '',
      ),
    );
    $fields[] = $field;
  }

  // Add fields.
  $form['collection_pages']['arteleku_collection_page_collection_data']['fields'] = $fields;

  // Limit results per page.
  $form['collection_pages']['arteleku_collection_page_limit'] = array(
    '#type' => 'textfield',
    '#title' => t('Results per page'),
    '#size' => 5,
    '#default_value' => variable_get('arteleku_collection_page_limit', 25),
    '#description' => t('The amount of values returned per page. Leave empty to return all fields'),
  );

  // Limit maximum results to be returned.
  $form['collection_pages']['arteleku_collection_page_facet_limit'] = array(
    '#type' => 'select',
    '#title' => t('Maximum searchable facet values'),
    '#multiple' => FALSE,
    '#options' => array(),
    '#default_value' => variable_get('arteleku_collection_page_facet_limit', 100000),
    '#description' => t('The maximum amount of values returned per page. A higher number cause slow page loads.'),
  );
  $options = array(
    1000,
    5000,
    10000,
    100000,
    250000,
    500000,
    750000,
    1000000,
    2500000,
    5000000,
    7500000,
    10000000,
  );

  foreach ($options as $o) {
    $form['collection_pages']['arteleku_collection_page_facet_limit']['#options'][$o] = number_format($o);
  }

  $form['buttons']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 50,
  );
  $form['buttons']['reset'] = array(
    '#type' => 'submit',
    '#value' => t('Reset to defaults'),
    '#weight' => 51,
  );

  return $form;
}

/**
 * Theme callback.
 */
function theme_arteleku_collection_page_admin_table(&$vars) {
  // Set variable.
  $rows = array();
  $form = $vars['form'];

  // Render islandora_solr_primary_display_table.
  foreach ($form['fields'] as $key => $elements) {

    $row = array();
    // Check if $key is really the array we need. we shouldn't select the
    // #parent array for example.
    if (is_array($elements) && element_child($key)) {

      $row[] = array('data' => drupal_render($form['fields'][$key]['collection_field']));
      $row[] = array('data' => drupal_render($form['fields'][$key]['label']));
      $row[] = array('data' => drupal_render($form['fields'][$key]['path']));
      $rows[] = $row;
    }
  }

  // Individual table headers.
  $header = array();
  $header[] = array('data' => t('Collection field'));
  $header[] = array('data' => t('Label'));
  $header[] = array('data' => t('Path'));

  // Render table.
  $output = theme('table', array(
    'header' => $header,
    'rows' => $rows,
    'attributes' => array(
      'id' => 'islandora-solr-facet-pages-admin-table',
    ),
  ));

  return $output;
}

/**
 * Implements hook_validate().
 */
function arteleku_collection_page_admin_settings_validate($form, &$form_state) {

  // On save.
  if ($form_state['clicked_button']['#id'] == 'edit-submit') {

    // Check for valid paths.
    foreach ($form_state['values']['arteleku_collection_page_collection_data']['fields'] as $key => $value) {
      if (!preg_match("/^[a-zA-Z0-9-_]*$/", $value['path'])) {
        form_set_error('arteleku_collection_page_collection_data][fields][' . $key . '][path', t('The path can only contain the following characters: a-z, A-Z, 0-9, - and _'));
      }
    }

    // Get limit value.
    $limit = $form_state['values']['arteleku_collection_page_limit'];
    $limit = trim($limit);
    // Check numeric.
    if (!is_numeric($limit)) {
      form_set_error('arteleku_collection_page_limit', t('Exposed results must be numeric.'));
    }
    // Check for no decimals.
    if (strpos($limit, '.')) {
      form_set_error('arteleku_collection_page_limit', t('Exposed results cannot be decimals.'));
    }
  }
}

/**
 * Implements hook_submit().
 */
function arteleku_collection_page_admin_settings_submit($form, &$form_state) {

  // Get operation.
  $op = $form_state['triggering_element']['#id'];

  switch ($op) {
    case 'edit-submit':
      // Set variables.
      // Clean up array.
      $fields_data = $form_state['values']['arteleku_collection_page_collection_data']['fields'];
      foreach ($fields_data as $key => $value) {
        if (empty($value['collection_field']) AND empty($value['label']) AND empty($value['path'])) {
          unset($fields_data[$key]);
        }
      }
      variable_set('arteleku_collection_page_collection_data', $fields_data);
      variable_set('arteleku_collection_page_limit', trim($form_state['values']['arteleku_collection_page_limit']));
      variable_set('arteleku_collection_page_facet_limit', $form_state['values']['arteleku_collection_page_facet_limit']);

      drupal_set_message(t('The configuration options have been saved.'));
      break;

    case 'edit-reset':
      // Empty variables.
      // Remove variables.
      $variables = array(
        'arteleku_collection_page_collection_data',
        'arteleku_collection_page_limit',
        'arteleku_collection_page_facet_limit',
      );
      foreach ($variables as $variable) {
        variable_del($variable);
      }

      drupal_set_message(t('The configuration options have been reset to their default values.'));
      break;
  }
}
