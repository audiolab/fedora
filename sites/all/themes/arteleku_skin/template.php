<?php


//theme_date_display_range

function arteleku_skin_date_display_range($variables){

  //dpm($variables);

  $date1 = $variables['date1'];
  $date2 = $variables['date2'];

  $formato = $variables['dates']['format'];
  $timezone = $variables['timezone'];

  $fecha1 = new DateObject($date1, $timezone, $formato);
  $fecha2 = new DateObject($date2, $timezone, $formato);
  $fromYear = ($fecha1->hasGranularity(array('year'))) ? $fecha1->toArray() : null;
  $toYear = ($fecha2->hasGranularity(array('year'))) ? $fecha2->toArray() : null;

  if(($fromYear !=null) && ($toYear != null)){
    if($fromYear['year']== $toYear['year'] && $formato != 'H:i'){
      $formato = variable_get('date_format_corto_sin_ano', 'm/d');
      $date1 = $fecha1->format($formato);
    }
  }


  $attributes_start = $variables['attributes_start'];
  $attributes_end = $variables['attributes_end'];

  // Wrap the result with the attributes.
  return t('!start-date - !end-date', array(
    '!start-date' => '<span class="date-display-start"' . drupal_attributes($attributes_start) . '>' . $date1 . '</span>',
    '!end-date' => '<span class="date-display-end"' . drupal_attributes($attributes_end) . '>' . $date2 . $timezone . '</span>',
  ));

  //return "fhsdf";
}

function arteleku_skin_form_alter(&$form, &$form_state, $form_id) {

  if ($form_id == 'search_block_form') {
    $form['search_block_form']['#title'] = t('Search'); // Change the text on the label element
    $form['search_block_form']['#title_display'] = 'invisible'; // Toggle label visibilty
    $form['search_block_form']['#size'] = 20;  // define size of the textfield
    $form['search_block_form']['#default_value'] = t('Search'); // Set a default value for the textfield
    $form['search_block_form']['#weight'] = 10;
    $form['search_block_form']['#attributes'] = array('class' => array('search-form-input'));
    $form['actions']['submit']['#value'] = t('GO!'); // Change the text on the submit button
    $form['actions']['submit'] = array('#type' => 'image_button', '#src' => base_path() . path_to_theme() . '/images/search-button.png');
    $form['actions']['#weight'] = 0;
    $form['actions']['#attributes'] = array('class' => array('search-form-lupa'));
    // Add extra attributes to the text box
    $form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'Search';}";
    $form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'Search') {this.value = '';}";
    // Prevent user from searching the default text
    $form['#attributes']['onsubmit'] = "if(this.search_block_form.value=='Search'){ alert('Please enter a search'); return false; }";

    // Alternative (HTML5) placeholder attribute instead of using the javascript
    $form['search_block_form']['#attributes']['placeholder'] = t('Search');
  }elseif ($form_id == 'apachesolr_panels_search_block') {

    $form['apachesolr_panels_search_block']['#title'] = t('Search'); // Change the text on the label element
    $form['apachesolr_panels_search_block']['#title_display'] = 'invisible'; // Toggle label visibilty
    $form['apachesolr_panels_search_block']['#size'] = 20;  // define size of the textfield
    $form['apachesolr_panels_search_block']['#default_value'] = t('Search'); // Set a default value for the textfield
    $form['apachesolr_panels_search_block']['#weight'] = 10;
    $form['apachesolr_panels_search_block']['#attributes'] = array('class' => array('search-form-input'));
    //$form['actions']['submit']['#value'] = t('GO!'); // Change the text on the submit button
    $form['actions']['submit'] = array('#type' => 'image_button', '#src' => base_path() . path_to_theme() . '/images/search-button.png');
    $form['actions']['#weight'] = 0;
    $form['actions']['#attributes'] = array('class' => array('search-form-lupa'));
    // Add extra attributes to the text box
    $form['apachesolr_panels_search_block']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'Search';}";
    $form['apachesolr_panels_search_block']['#attributes']['onfocus'] = "if (this.value == 'Search') {this.value = '';}";
    // Prevent user from searching the default text
   // $form['#attributes']['onsubmit'] = "if(this.search_block_form.value=='Search'){ alert('Please enter a search'); return false; }";

    // Alternative (HTML5) placeholder attribute instead of using the javascript
    $form['apachesolr_panels_search_block']['#attributes']['placeholder'] = t('Search');
  }elseif ($form_id == 'registration_form'){
  //  dpm($form);
    $form['who_message']['#access'] = false;
//    $form[]
  }

}

function arteleku_skin_menu_local_tasks(&$variables) {
  //OCultamos las etiquetas de administración para los usuarios que son de tipo suscriptor.
  global $user;
  $roles = $user->roles;
  if (in_array('suscriptor',$roles)){
    return;
  }
  else{
    $output = '';
    if (!empty($variables['primary'])) {
      $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
      $variables['primary']['#prefix'] .= '<ul class="tabs primary">';
      $variables['primary']['#suffix'] = '</ul>';
      $output .= drupal_render($variables['primary']);
    }
    if (!empty($variables['secondary'])) {
      $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
      $variables['secondary']['#prefix'] .= '<ul class="tabs secondary">';
      $variables['secondary']['#suffix'] = '</ul>';
      $output .= drupal_render($variables['secondary']);
    }
  }
  return $output;

}




function arteleku_skin_more_link(&$variables){
   return '<div class="more-link">' . l(t('+'), $variables['url'], array('attributes' => array('title' => $variables['title']))) . '</div>';
}


function arteleku_skin_preprocess_galleria_container(&$vars) {
  // Each Galleria instance gets a unique id
  $galleria_id = &drupal_static('galleria_id', 0);
  $vars['id'] = ++$galleria_id;

  // Load the used option set
  if (!empty($vars['settings']['optionset'])) {
    $optionset = galleria_optionset_load($vars['settings']['optionset']);
  }
  if (empty($optionset)) {
    // Fall back to 'default' option set
    $optionset = galleria_optionset_load('default');
  }

  // Attach Galleria JavaScript
  galleria_add_js($galleria_id, $optionset);

  // Prepare image elements
  $items = $vars['items'];

  $vars['items'] = array();

  $thumb_style = empty($optionset['imagestyle_thumb']) ? 'galleria_thumb' : $optionset['imagestyle_thumb'];
  foreach ($items as $delta => $item) {

    // Stop errors for empty URI. See issue [#1319268] for details.
    if (empty($item['#item']['uri'])) {
      continue;
    }

    $f = file_load($item['#item']['fid']);
    $r = file_view($f, $thumb_style, NULL);
    $r2 = file_view($f, $optionset['imagestyle_normal'], NULL);
    $r['galleria'] = array(
      'path' => $r2['file']['#path'],
      'options' => array(
        'html' => TRUE,
        'attributes' => array(
          'rel' => $r2['file']['#path'],
        )
      )
      );
    $vars['items'][$delta] = $r;
  }
}
/**
*
*/


/**
 * Create the calendar date box.
 */
function arteleku_skin_preprocess_calendar_datebox(&$vars) {

  $date = $vars['date'];
  $view = $vars['view'];
  $vars['day'] = intval(substr($date, 8, 2));
  $force_view_url = !empty($view->date_info->block) ? TRUE : FALSE;
  $month_path = calendar_granularity_path($view, 'month');
  $year_path = calendar_granularity_path($view, 'year');
  $day_path = calendar_granularity_path($view, 'day');
  //dpm($day_path);
  $day_path="agenda";
  $vars['url'] = str_replace(array($month_path, $year_path), $day_path, date_pager_url($view, NULL, $date, $force_view_url));
  $vars['link'] = !empty($day_path) ? l($vars['day'], $vars['url']) : $vars['day'];
  $vars['granularity'] = $view->date_info->granularity;
  $vars['mini'] = !empty($view->date_info->mini);
  if ($vars['mini']) {
    if (!empty($vars['selected'])) {
      $vars['class'] = 'mini-day-on';
    }
    else {
      $vars['class'] = 'mini-day-off';
    }
  }
  else {
    $vars['class'] = 'day';
  }
}

function arteleku_skin_preprocess_search_results(&$vars) {

  drupal_add_js(drupal_get_path('theme', 'arteleku_skin') .'/js/jquery.masonry.js', 'file');
  drupal_add_js(drupal_get_path('theme', 'arteleku_skin') .'/js/search.js', 'file');

}


/**
 * Theme handler for registration links.
 *
 * @param array $variables
 *   Contains the label and path for the link.
 */
function arteleku_skin_registration_link($variables) {
  $output = '';
  $registration_label = $variables['label'];
  $registration_path = $variables['path'];

  $output .= l($registration_label, $registration_path, array( 'attributes' => array('class'=>'register-link')));

  return $output;
}

/**
 * Theme function for unified login page.
 *
 * @ingroup themable
 */
function arteleku_skin_lt_unified_login_page($variables) {

  $login_form = $variables['login_form'];
  $register_form = $variables['register_form'];
  $active_form = $variables['active_form'];
  $output = '';

  $output .= '<div class="toboggan-unified ' . $active_form . '">';

  // Create the initial message and links that people can click on.
  $output .= '<div id="login-message"><h3>' . t('You are not logged in.') . '</h3></div>';
  $output .= '<div id="login-wrapper">';
  $output .= '<div id="login-links">';
  $output .= l(t('I have an account'), 'user/login', array('attributes' => array('class' => array('login-link'), 'id' => 'login-link')));
  $output .= ' ';
  $output .= l(t('I want to create an account'), 'user/register', array('attributes' => array('class' => array('login-link'), 'id' => 'register-link')));

  $output .= '</div>';

  // Add the login and registration forms in.
  $output .= '<div id="login-form">' . $login_form . '</div>';
  $output .= '<div id="register-form">' . $register_form . '</div>';
  $output .= '</div>';
  $output .= '</div>';

  return $output;
}

function arteleku_skin_feed_icon($url) {
  //$icon_url = '/' . drupal_get_path('theme', 'yourthemename') . '/images/feed.png';
//  if ($image = '<img src="'. $icon_url . '" alt="'. t('RSS feed') .'" />') {
    return '<a class="podcast-icon" href="'. check_url($url['url']) .'"><span class="element-invisible">Podcast</span></a>';
   // return "kjdshfksdjhfdskjhfkjdshfkjsd";
  //}
}


/**
 * Implements template_preprocess_HOOK().
 */


function arteleku_skin_preprocess_islandora_basic_collection_grid(&$variables){
    global $base_url;
    global $base_path;
    $islandora_object = $variables['islandora_object'];

    try {
        $dc = $islandora_object['DC']->content;
        $dc_object = DublinCore::importFromXMLString($dc);
    }
    catch (Exception $e) {
        drupal_set_message(t('Error retrieving object %s %t', array('%s' => $islandora_object->id, '%t' => $e->getMessage())), 'error', FALSE);
    }
    $page_number = (empty($_GET['page'])) ? 0 : $_GET['page'];
    $page_size = (empty($_GET['pagesize'])) ? variable_get('islandora_basic_collection_page_size', '10') : $_GET['pagesize'];
    $results = $variables['collection_results'];
    $total_count = count($results);
    $variables['islandora_dublin_core'] = isset($dc_object) ? $dc_object : array();
    $variables['islandora_object_label'] = $islandora_object->label;
    $display = (empty($_GET['display'])) ? 'list' : $_GET['display'];
    if ($display == 'grid') {
        $variables['theme_hook_suggestions'][] = 'islandora_basic_collection_grid__' . str_replace(':', '_', $islandora_object->id);
    }
    else {
        $variables['theme_hook_suggestions'][] = 'islandora_basic_collection__' . str_replace(':', '_', $islandora_object->id);
    }
    if (isset($islandora_object['OBJ'])) {
        $full_size_url = url("islandora/object/{$islandora_object->id}/datastram/OBJ/view");
        $params = array(
            'title' => $islandora_object->label,
            'path' => $full_size_url);
        $variables['islandora_full_img'] = theme('image', $params);
    }
    if (isset($islandora_object['TN'])) {
        $full_size_url = url("islandora/objects/{$islandora_object->id}/datastream/TN/view");
        $params = array(
            'title' => $islandora_object->label,
            'path' => $full_size_url);
        $variables['islandora_thumbnail_img'] = theme('image', $params);
    }
    if (isset($islandora_object['MEDIUM_SIZE'])) {
        $full_size_url = url("islandora/object/{$islandora_object->id}/datastream/MEDIUM_SIZE/view");
        $params = array(
            'title' => $islandora_object->label,
            'path' => $full_size_url);
        $variables['islandora_medium_img'] = theme('params', $params);
    }

    $associated_objects_array_mods = array();

    foreach ($results as $result) {
        $pid = $result['object']['value'];
        $fc_object = islandora_object_load($pid);
        if (!is_object($fc_object)) {
            // NULL object so don't show in collection view.
            continue;
        }
        $associated_objects_array_mods[$pid]['object'] = $fc_object;
        try {
            if (!isset($fc_object['MODS'])){continue;}
            $mods = $fc_object['MODS']->content;
            $mods_object = Mods::importFromXMLString($mods);
            $associated_objects_array_mods[$pid]['mods_array'] = theme('mods_object', array('mods' => $mods_object));
        }
        catch (Exception $e) {
            drupal_set_message(t('Error retrieving object %s %t', array('%s' => $islandora_object->id, '%t' => $e->getMessage())), 'error', FALSE);
        }
        $object_url = 'islandora/object/' . $pid;

        $title = $associated_objects_array_mods[$pid]['mods_array']['titleInfo'];//$result['title']['value'];
        if(isset($associated_objects_array_mods[$pid]['mods_array']['part'])){
            $part = "";
            if (!empty($mods_object->mods['part'])){
                $l_item = _islandora_get_localized($mods_object->mods['part']);
                $part = theme("modselement__part", array('element' => $l_item, 'title' => TRUE));
            }
            $title = $associated_objects_array_mods[$pid]['mods_array']['titleInfo'] . ' ' . $part;
        }

        $associated_objects_array_mods[$pid]['pid'] = $pid;
        $associated_objects_array_mods[$pid]['path'] = $object_url;
        $associated_objects_array_mods[$pid]['title'] = $title;
        $associated_objects_array_mods[$pid]['class'] = drupal_strtolower(preg_replace('/[^A-Za-z0-9]/', '-', $pid));
        if (isset($fc_object['TN'])) {
            $thumbnail_img = theme('image', array('path' => "$object_url/datastream/TN/view"));
        }
        else {
            $image_path = drupal_get_path('module', 'islandora');
            $thumbnail_img = theme('image', array('path' => "$image_path/images/folder.png"));
        }
        $associated_objects_array_mods[$pid]['thumbnail'] = $thumbnail_img;
        $associated_objects_array_mods[$pid]['title_link'] = l($title, $object_url, array('html' => TRUE, 'attributes' => array('title' => $title)));
        $associated_objects_array_mods[$pid]['thumb_link'] = l($thumbnail_img, $object_url, array('html' => TRUE, 'attributes' => array('title' => $title)));

    }
    $variables['associated_objects_array_mods'] = $associated_objects_array_mods;
}

function arteleku_skin_preprocess_islandora_pdf(&$variables) {
    $object = $variables['islandora_object'];
    if (isset($object['OBJ']) && $object['OBJ']->mimetype == 'application/pdf') {
        $dsid = 'OBJ';
    }
    else {
        $dsid = 'PDF';
    }
    $file_url = url("islandora/object/{$object->id}/datastream/$dsid/view", array('absolute' => TRUE));
    $variables['viewer_url'] = url( '../sites/all/libraries/pdfjs/web/viewer.html', array('absolute' => TRUE, 'query' => array('file' => $file_url)));
}

function arteleku_skin_preprocess_islandora_solr_wrapper(&$variables){

	//dpm(print_r($variables, true));

}

function arteleku_skin_preprocess_islandora_solr(&$variables) {
//	dpm(print_r($variables, true));
    $associated_objects_array_mods = array();
	$results = $variables['results'];

    foreach ($results as $result) {
        $pid = $result['PID'];
        $fc_object = islandora_object_load($pid);
        if (!is_object($fc_object)) {
            // NULL object so don't show in collection view.
            continue;
        }
        $associated_objects_array_mods[$pid]['object'] = $fc_object;
        try {
            if (!isset($fc_object['MODS'])){continue;}
            $mods = $fc_object['MODS']->content;
            $mods_object = Mods::importFromXMLString($mods);
            $associated_objects_array_mods[$pid]['mods_array'] = theme('mods_object', array('mods' => $mods_object));
        }
        catch (Exception $e) {
            drupal_set_message(t('Error retrieving object %s %t', array('%s' => $islandora_object->id, '%t' => $e->getMessage())), 'error', FALSE);
        }
        $object_url = 'islandora/object/' . $pid;

        $title = $associated_objects_array_mods[$pid]['mods_array']['titleInfo'];//$result['title']['value'];
        if(isset($associated_objects_array_mods[$pid]['mods_array']['part'])){
            $part = "";
            if (!empty($mods_object->mods['part'])){
                $l_item = _islandora_get_localized($mods_object->mods['part']);
                $part = theme("modselement__part", array('element' => $l_item, 'title' => TRUE));
            }
            $title = $associated_objects_array_mods[$pid]['mods_array']['titleInfo'] . ' ' . $part;
        }

        $associated_objects_array_mods[$pid]['pid'] = $pid;
        $associated_objects_array_mods[$pid]['path'] = $object_url;
        $associated_objects_array_mods[$pid]['title'] = $title;
        $associated_objects_array_mods[$pid]['class'] = drupal_strtolower(preg_replace('/[^A-Za-z0-9]/', '-', $pid));
        if (isset($fc_object['TN'])) {
            $thumbnail_img = theme('image', array('path' => "$object_url/datastream/TN/view"));
        }
        else {
            $image_path = drupal_get_path('module', 'islandora');
            $thumbnail_img = theme('image', array('path' => "$image_path/images/folder.png"));
        }
        $associated_objects_array_mods[$pid]['thumbnail'] = $thumbnail_img;
        $associated_objects_array_mods[$pid]['title_link'] = l($title, $object_url, array('html' => TRUE, 'attributes' => array('title' => $title)));
        $associated_objects_array_mods[$pid]['thumb_link'] = l($thumbnail_img, $object_url, array('html' => TRUE, 'attributes' => array('title' => $title)));

    }
    $variables['associated_objects_array_mods'] = $associated_objects_array_mods;

}

function arteleku_skin_preprocess_page(&$variables)
{

//	dvm($variables);

}

