<?php

/**
 * @file
 * Functions for generating/validating/submitting the add datastream form.
 */

/**
 * The admin add datastream form.
 *
 * @param array $form
 *   The Drupal form.
 * @param array $form_state
 *   The Drupal form state.
 * @param AbstractObject $object
 *   The object to be deleted.
 *
 * @return array
 *   The drupal form definition.
 */
function islandora_add_datastream_form(array $form, array &$form_state, AbstractObject $object) {
  module_load_include('inc', 'islandora', 'includes/content_model');
  module_load_include('inc', 'islandora', 'includes/utilities');
  form_load_include($form_state, 'inc', 'islandora', 'includes/add_datastream.form');
  $form_state['object'] = $object;
  $form_state['datastream_requirements'] = islandora_get_missing_datastreams_requirements($object);
  $unused_datastreams = array_keys($form_state['datastream_requirements']);
  $unused_datastreams = "'" . implode("', '", $unused_datastreams) . "'";
  $upload_size = min((int) ini_get('post_max_size'), (int) ini_get('upload_max_filesize'));
  return array(
    '#redirect' => "islandora/object/{$object->id}",
    '#attributes' => array(
      'enctype' => 'multipart/form-data',
    ),
    'dsid' => array(
      '#type' => 'fieldset',
      '#title' => 'Add a datastream',
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      'dsid' => array(
        '#title' => 'Datastream ID',
        '#description' => t("An ID for this stream that is unique to this object. Must start with a letter and contain only alphanumeric characters, dashes and underscores. The following datastreams are defined by this content model but don't currently exist: <b>@unused_dsids</b>.", array('@unused_dsids' => $unused_datastreams)),
      ),
      '#type' => 'textfield',
      '#size' => 64,
      '#maxlength' => 64,
      '#required' => TRUE,
      '#element_validate' => array(
        'islandora_add_datastream_form_field_is_not_an_existing_datastream_id',
        'islandora_add_datastream_form_field_starts_with_a_letter',
        'islandora_add_datastream_form_field_is_valid_dsid',
      ),
      '#autocomplete_path' => "islandora/object/{$object->id}/manage/datastreams/add/autocomplete",
    ),
    'label' => array(
      '#title' => 'Datastream Label',
      '#required' => TRUE,
      '#size' => 64,
      '#maxlength' => 64,
      '#description' => t('A human-readable label'),
      '#type' => 'textfield',
      '#element_validate' => array('islandora_add_datastream_form_field_does_not_contain_a_forward_slash'),
    ),
    'file' => array(
      '#type' => 'managed_file',
      '#required' => TRUE,
      '#title' => t('Upload Document'),
      '#size' => 48,
      '#description' => t('Select a file to upload.<br/>Files must be less than <b>@size MB.</b>', array('@size' => $upload_size)),
      '#default_value' => isset($form_state['values']['files']) ? $form_state['values']['files'] : NULL,
      '#upload_location' => 'temporary://',
      '#upload_validators' => array(
        'file_validate_extensions' => array(NULL),
        // Assume its specified in MB.
        'file_validate_size' => array($upload_size * 1024 * 1024),
      ),
    ),
    'submit' => array(
      '#type' => 'submit',
      '#value' => t('Add Datastream'),
    ),
  );
}

/**
 * Checks if the given form field doesn't already repersent a Datastream ID.
 *
 * @param array $element
 *   The form field to check.
 * @param array $form_state
 *   The Drupal form state.
 * @param array $form
 *   The Drupal form.
 */
function islandora_add_datastream_form_field_is_not_an_existing_datastream_id(array $element, array &$form_state, array $form) {
  if (isset($form_state['object'][$element['#value']])) {
    form_error($element, t("@title already exists in the object.", array('@title' => $element['#title'])));
  }
}

/**
 * Checks if the given form field starts with a letter.
 *
 * @param array $element
 *   The form field to check.
 * @param array $form_state
 *   The Drupal form state.
 * @param array $form
 *   The Drupal form.
 */
function islandora_add_datastream_form_field_starts_with_a_letter(array $element, array &$form_state, array $form) {
  if (!(preg_match("/^[a-zA-Z]/", $element['#value']))) {
    form_error($element, t("@title has to start with a letter.", array('@title' => $element['#title'])));
  }
}

/**
 * Checks if the given form field contains a valid datastream ID.
 *
 * @param array $element
 *   The form field to check.
 * @param array $form_state
 *   The Drupal form state.
 * @param array $form
 *   The Drupal form.
 */
function islandora_add_datastream_form_field_is_valid_dsid(array $element, array &$form_state, array $form) {
  module_load_include('inc', 'islandora', 'includes/utilities');
  if (!islandora_is_valid_dsid($element['#value'])) {
    form_error($element, t("@title contains invalid characters.", array('@title' => $element['#title'])));
  }
}

/**
 * Checks if the given form field contains a "/" character.
 *
 * @param array $element
 *   The form field to check.
 * @param array $form_state
 *   The Drupal form state.
 * @param array $form
 *   The Drupal form.
 */
function islandora_add_datastream_form_field_does_not_contain_a_forward_slash(array $element, array &$form_state, array $form) {
  if (strpos($element['#value'], '/') !== FALSE) {
    form_error($element, t('@title cannot contain a "/" character.', array('@title' => $element['#title'])));
  }
}

/**
 * Checks if the given datastream requires the upload to be a certian MIME type.
 *
 * @param array $form
 *   The Drupal form.
 * @param array $form_state
 *   The Drupal form state.
 */
function islandora_add_datastream_form_validate(array $form, array &$form_state) {
  $file = file_load($form_state['values']['file']);
  $dsid = $form_state['values']['dsid'];
  if (isset($form_state['datastream_requirements'][$dsid]) && $file) {
    $allowed_types = $form_state['datastream_requirements'][$dsid]['mime'];
    $mime_detect = new MimeDetect();
    $allowed_extensions = array_map(array($mime_detect, 'getExtension'), $allowed_types);
    $errors = file_validate_extensions($file, implode(' ', $allowed_extensions));
    if (count($errors) > 0) {
      form_set_error('file', $errors[0]);
    }
  }
}

/**
 * Adds the new datastream based on the submitted values.
 *
 * Only creates managed datastreams at the moment.
 *
 * @param array $form
 *   The Drupal form.
 * @param array $form_state
 *   The Drupal form state.
 */
function islandora_add_datastream_form_submit(array $form, array &$form_state) {
  $object = $form_state['object'];
  $form_state['redirect'] = "islandora/object/{$object->id}";
  $file = file_load($form_state['values']['file']);
  try {
    $ds = $object->constructDatastream($form_state['values']['dsid'], 'M');
    $ds->label = $form_state['values']['label'];
    $ds->mimetype = $file->filemime;
    $path = drupal_realpath($file->uri);
    $ds->setContentFromFile($path);
    $object->ingestDatastream($ds);
    file_delete($file);
  }
  catch (exception $e) {
    drupal_set_message(t('@message', array('@message' => check_plain($e->getMessage()))), 'error');
    // Make sure to delete anyways.
    file_delete($file);
    return;
  }
  drupal_set_message(t("Successfully Added Datastream!"));
}

/**
 * Callback for an autocomplete field in the admin add datastream form.
 *
 * It lists the missing required (may be optional) datastreams.
 *
 * @param AbstractObject $object
 *   The object used to check for missing required datastreams used to populate
 *    the options in this callback.
 * @param string $query
 *   vThe user query to match against the missing required datastreams.
 */
function islandora_add_datastream_form_autocomplete_callback(AbstractObject $object, $query = '') {
  module_load_include('inc', 'islandora', 'includes/content_model');
  module_load_include('inc', 'islandora', 'includes/utilities');
  $dsids = array_keys(islandora_get_missing_datastreams_requirements($object));
  $dsids = array_combine($dsids, $dsids);
  $query = trim($query);
  if (!empty($query)) {
    $filter = function($id) use($query) {
      return stripos($id, $query) !== FALSE;
    };
    $dsids = array_filter($dsids, $filter);
  }
  drupal_json_output($dsids);
}
