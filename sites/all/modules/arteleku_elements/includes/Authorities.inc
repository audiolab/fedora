<?php

/**
 * @file
 *
 */
module_load_include('inc', 'arteleku_elements', 'includes/Authority');

class Authorities {

  /**
   * Loads the required resources for displaying the Tabs element.
   *
   * @staticvar boolean $load
   *   Keeps us from loading the same files multiple times, while not required it just saves some time.
   */
  public static function addRequiredResources(array &$form_state) {
	static $load = TRUE;
	if($load){
      form_load_include($form_state, 'inc', 'xml_form_elements', 'includes/Element');
      Element::addRequiredResources();
	  drupal_add_js(drupal_get_path('module', 'arteleku_elements') . '/js/select2.js');
	  drupal_add_js(drupal_get_path('module', 'arteleku_elements') . '/js/authority.js');
	  drupal_add_css(drupal_get_path('module', 'arteleku_elements') . '/css/select2.css');
	  $load = FALSE;
	}
  }

  /**
   *
   * @param array $element
   * @param type $edit
   * @param array $form_state
   * @param array $complete_form
   */
  public static function Process(array $element, array &$form_state, array $complete_form = NULL) {
    self::addRequiredResources($form_state);
	$element['#id'] = $element['#hash'];   
	$element['#size'] = 60;
	$element['#prefix'] = "<div class='clear-block' id='ahah-{$element['#id']}-wrapper'>";
	$element['#suffix'] = '</div>';
	$element['#tree'] = TRUE;
	foreach(element_children($element) as $key){
		$child = $element[$key];
		$hash = $child['#hash'];
	}

	$element['add'] = self::createAddButton($element);
	$element['select'] = self::createSelect($element);
	return $element;
  }

	private static function createSelect(array &$element){
	
	  // First tag is the textfield, all others show up as icons with hidden inputs.
  	$tax = taxonomy_get_tree(4);
	$options = array();
	foreach ($tax as $term){
		$options[$term->tid] = $term->name;
	}
	$select = array(
			'#type' => 'select',
			'#options' => $options,
			'#multiple' => 'multiple',
			'#size' => 30,
			'#attributes' => array(
				'class' => array('select2'),
				'style' => 'width:300px;',
				'multiple'=> 'multiple',
				'data-hash' => $element['#hash'])
			);
 	
	$select['#id'] = $select['#name'] = $element['#hash'] . 'select';
	
	return $select;
	
	
	}

  private static function createAddButton(array &$element) {
    $button['#type'] = 'button';
    $button['#weight'] = 4;
    $button['#size'] = 30;
    $button['#id'] = $button['#name'] = $element['#hash'] . '-add';
	$button['#attributes'] = array('style' => 'display:none');
	$button['#limit_validation_errors'][]= $element['#parents'];
    $button['#value'] = t('Add');
    $button['#ajax'] = array(
		'callback' => 'arteleku_elements_ajax_callback',
		'wrapper' => "ahah-{$element['#id']}-wrapper",
		'method' => 'replaceWidth',
    	'params' => array(
        	'render' => $element['#hash'],
        	'target' => $element['#hash'],
			'action' => 'add'
      	),
    );
    return $button;
  }

  private static function createRemoveButton(array &$element, array &$child) {
    $button['#type'] = 'button';
    $button['#weight'] = 4;
    $button['#size'] = 30;
    $button['#id'] = $button['#name'] = $child['#hash'] . '-remove';
    $button['#attributes'] = array('style' => 'display:none');
    $button['#limit_validation_errors'][] = $element['#parents'];
    $button['#ajax'] = array(
		'callback' => 'arteleku_elements_ajax_callback',
		'method' => 'replaceWidth',
      'params' => array(
        'render' => $element['#hash'],
        'target' => $element['#hash'],
		'child' => $child['#hash'],
		'action' => 'delete'
      ),
    );
    return $button;
  }

}
/*
function theme_authorities($vars){
	$el =  Authorities::Theme($vars['element']);
	$bt_add = theme('button', $vars['element']['controls']['add']);
	
	$out = '';
	$out = $el . $bt_add;
	return $out;
}
 */

/**
 * Preprocess tabs theme hook.
 *
 * @param array $vars
 **/
function template_preprocess_authorities(&$vars) {
	$authorities = &$vars['element'];

	$vars['authorities'] = array();
	$children = element_children($authorities);
	foreach($children as $key){
		$child = $authorities[$key];
		if (isset($child['#type']) && ($child['#type'] == 'authority')){
			$vars['authorities'][$key] = $child;
		}
	
	}
	$vars['select'] = render($authorities['select']);
	$vars['add'] = $authorities['add'];
//	$vars['control_add'] = render ($authorities['controls']['add']);
}
