<?php

function islandora_solr_ajax($query= NULL){
  $params = $_GET;
  // Get profiles.
  $primary_profiles = module_invoke_all('islandora_solr_primary_display');
  $secondary_profiles = module_invoke_all('islandora_solr_secondary_display');

  // Get the preferred display profile.
  // Order:
  // - $_GET['display'].
  // - The default primary display profile.
  // - Third choice is the base IslandoraSolrResults.
  $enabled_profiles = array();
  // Get enabled displays.
  $primary_display_array = variable_get('islandora_solr_primary_display_table', array());
  // If it's set, we take these values.
  if (isset($primary_display_array['enabled'])) {
    foreach ($primary_display_array['enabled'] as $key => $value) {
      if ($key === $value) {
        $enabled_profiles[] = $key;
      }
    }
  }
  // Set primary display.
  // Check if display param is an valid, enabled profile; otherwise, show
  // default.
  if (isset($params['display']) && in_array($params['display'], $enabled_profiles)) {
    $islandora_solr_primary_display = $params['display'];
  }
  else {
    $islandora_solr_primary_display = variable_get('islandora_solr_primary_display', 'default');
    // Unset invalid parameter.
    unset($params['display']);
  }

  // !!! Set the global variable. !!!
  $_islandora_solr_queryclass = new IslandoraSolrQueryProcessor();

  // Build and execute Apache Solr query.
  $_islandora_solr_queryclass->buildAndExecuteQuery($query, $params);

  if (empty($_islandora_solr_queryclass->islandoraSolrResult)) {
    return t('Error searching Solr index.');
  }

  // TODO: Also filter secondary displays against those checked in the
  // configuration options.
  if (isset($params['solr_profile']) && isset($secondary_profiles[$params['solr_profile']])) {
    $profile = $secondary_profiles[$_GET['solr_profile']];
  }
  elseif (isset($primary_profiles[$islandora_solr_primary_display])) {
    $profile = $primary_profiles[$islandora_solr_primary_display];
  }
  else {
    drupal_set_message(check_plain(t('There is an error in the Solr search configuration: the display profile is not found.')), 'error');
    $profile = $primary_profiles['default'];
  }

  if (isset($profile['file'])) {
    // Include the file for the display profile.
    require_once drupal_get_path('module', $profile['module']) . '/' . $profile['file'];
  }

  // Get display class and function from current display.
  $solr_class = $profile['class'];
  $solr_function = $profile['function'];

  // Check if the display's class exists.
  $use_default_display = TRUE;
  if (class_exists($solr_class)) {
    $implementation = new $solr_class();
    // Check if the display's method exists.
    if (method_exists($implementation, $solr_function)) {
      // Implement results.
      $output = $implementation->$solr_function($_islandora_solr_queryclass);
      $use_default_display = FALSE;
    }
  }

  // Class and method could not be found, so use default.
  if ($use_default_display) {
    $results_class = new IslandoraSolrResults();
    $output = $results_class->displayResults($_islandora_solr_queryclass);
  }

  // Debug dump.
  if (variable_get('islandora_solr_debug_mode', 0)) {
    $message = t('Parameters: <br /><pre>!debug</pre>', array('!debug' => print_r($_islandora_solr_queryclass->solrParams, TRUE)));
    drupal_set_message(filter_xss($message, array('pre', 'br')), 'status');
  }
	drupal_json_output(array('status' => 0, 'data' => $output));
}
